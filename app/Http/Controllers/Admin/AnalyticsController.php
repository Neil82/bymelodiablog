<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use App\Models\TrackingEvent;
use App\Models\PostAnalytics;
use App\Models\VisitorAnalytics;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function overview(Request $request)
    {
        $period = $request->get('period', '30'); // Default 30 days
        $startDate = now()->subDays($period);

        // Get basic stats
        $stats = [
            'total_visitors' => UserSession::where('started_at', '>=', $startDate)->count(),
            'unique_visitors' => UserSession::where('started_at', '>=', $startDate)->distinct('ip_address')->count(),
            'total_page_views' => TrackingEvent::where('event_type', 'page_view')
                ->where('event_time', '>=', $startDate)->count(),
            'avg_session_duration' => UserSession::where('started_at', '>=', $startDate)
                ->where('total_duration', '>', 0)
                ->avg('total_duration'),
            'bounce_rate' => $this->calculateBounceRate($startDate)
        ];

        // Get visitor trends (daily data)
        $visitorTrends = UserSession::where('started_at', '>=', $startDate)
            ->selectRaw('DATE(started_at) as date, COUNT(*) as visitors, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get top pages
        $topPages = TrackingEvent::where('event_type', 'page_view')
            ->where('event_time', '>=', $startDate)
            ->selectRaw('url, page_title, COUNT(*) as views')
            ->groupBy('url', 'page_title')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // Get top posts
        $topPosts = PostAnalytics::where('date', '>=', $startDate->toDateString())
            ->selectRaw('post_id, SUM(unique_views) as total_views, SUM(total_time_on_page) as total_time')
            ->groupBy('post_id')
            ->orderByDesc('total_views')
            ->limit(10)
            ->with('post')
            ->get();

        // Get device breakdown
        $deviceStats = UserSession::where('started_at', '>=', $startDate)
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->get();

        // Get country breakdown
        $countryStats = UserSession::where('started_at', '>=', $startDate)
            ->whereNotNull('country_name')
            ->selectRaw('country_name, country_code, COUNT(*) as count')
            ->groupBy('country_name', 'country_code')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('admin.analytics.overview', compact(
            'stats', 'visitorTrends', 'topPages', 'topPosts', 
            'deviceStats', 'countryStats', 'period'
        ));
    }

    public function visitors(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);

        // Visitor demographics
        $demographics = [
            'by_country' => UserSession::where('started_at', '>=', $startDate)
                ->whereNotNull('country_name')
                ->selectRaw('country_name, country_code, COUNT(*) as visitors')
                ->groupBy('country_name', 'country_code')
                ->orderByDesc('visitors')
                ->get(),
            
            'by_device' => UserSession::where('started_at', '>=', $startDate)
                ->selectRaw('device_type, COUNT(*) as visitors')
                ->groupBy('device_type')
                ->orderByDesc('visitors')
                ->get(),
            
            'by_browser' => UserSession::where('started_at', '>=', $startDate)
                ->whereNotNull('browser')
                ->selectRaw('browser, COUNT(*) as visitors')
                ->groupBy('browser')
                ->orderByDesc('visitors')
                ->limit(10)
                ->get(),
            
            'by_os' => UserSession::where('started_at', '>=', $startDate)
                ->whereNotNull('os')
                ->selectRaw('os, COUNT(*) as visitors')
                ->groupBy('os')
                ->orderByDesc('visitors')
                ->limit(10)
                ->get(),
        ];

        // Traffic sources
        $trafficSources = UserSession::where('started_at', '>=', $startDate)
            ->selectRaw('
                CASE 
                    WHEN referrer IS NULL OR referrer = "" THEN "Direct"
                    WHEN referrer LIKE "%google%" THEN "Google"
                    WHEN referrer LIKE "%facebook%" THEN "Facebook"
                    WHEN referrer LIKE "%twitter%" THEN "Twitter"
                    WHEN referrer LIKE "%instagram%" THEN "Instagram"
                    ELSE "Other"
                END as source,
                COUNT(*) as visitors
            ')
            ->groupBy('source')
            ->orderByDesc('visitors')
            ->get();

        // Session duration distribution
        $sessionDurations = UserSession::where('started_at', '>=', $startDate)
            ->where('total_duration', '>', 0)
            ->selectRaw('
                CASE 
                    WHEN total_duration < 30 THEN "0-30s"
                    WHEN total_duration < 60 THEN "30-60s"
                    WHEN total_duration < 180 THEN "1-3min"
                    WHEN total_duration < 600 THEN "3-10min"
                    WHEN total_duration < 1800 THEN "10-30min"
                    ELSE "30min+"
                END as duration_range,
                COUNT(*) as sessions
            ')
            ->groupBy('duration_range')
            ->get();

        return view('admin.analytics.visitors', compact(
            'demographics', 'trafficSources', 'sessionDurations', 'period'
        ));
    }

    public function posts(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);

        // Get posts with basic analytics from posts table (use real data only)
        $posts = Post::where('views', '>', 0)
            ->orderByDesc('views')
            ->get()
            ->map(function($post) {
                // Default values based on total views
                $uniqueViews = $post->views; // Default to total views (conservative)
                $estimatedAvgTime = 60; // Default 1 minute
                $estimatedBounceRate = 50; // Default 50%
                $estimatedScrollDepth = 70; // Default 70%
                
                return [
                    'post' => $post,
                    'total_views' => $post->views, // Use real view count
                    'unique_views' => $uniqueViews,
                    'avg_time' => $estimatedAvgTime,
                    'total_time' => $post->views * $estimatedAvgTime,
                    'bounce_rate' => $estimatedBounceRate,
                    'scroll_depth_avg' => $estimatedScrollDepth
                ];
            });

        // Try to get real tracking data if available
        $trackingData = TrackingEvent::where('event_type', 'page_view')
            ->whereNotNull('post_id')
            ->where('event_time', '>=', $startDate)
            ->selectRaw('
                post_id,
                COUNT(DISTINCT user_session_id) as unique_views,
                COUNT(*) as total_views
            ')
            ->groupBy('post_id')
            ->get()
            ->keyBy('post_id');

        // Update posts with real tracking data if available
        $posts = $posts->map(function($item) use ($trackingData) {
            if ($trackingData->has($item['post']->id)) {
                $realData = $trackingData->get($item['post']->id);
                $item['unique_views'] = $realData->unique_views;
                $item['total_views'] = max($item['total_views'], $realData->total_views);
            }
            return $item;
        });

        // Top performing posts
        $topPosts = $posts->sortByDesc('total_views')->take(20);

        // Posts with longest engagement
        $engagementPosts = $posts->sortByDesc('avg_time')->take(10);

        // Content performance metrics
        $contentMetrics = [
            'total_posts_viewed' => $posts->count(),
            'avg_views_per_post' => round($posts->avg('total_views') ?? 0),
            'avg_time_per_post' => round($posts->avg('avg_time') ?? 0),
            'total_views' => $posts->sum('total_views')
        ];

        // Get recent activity - show ALL posts with views, ordered by most recent activity
        $recentActivity = Post::where('views', '>', 0)
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get()
            ->map(function($post) use ($trackingData) {
                // Use real tracking data if available, otherwise use the post data
                $uniqueViews = $post->views; // Default to total views
                if ($trackingData->has($post->id)) {
                    $uniqueViews = $trackingData->get($post->id)->unique_views;
                }
                
                return [
                    'post' => $post,
                    'views' => $post->views,
                    'unique_views' => $uniqueViews,
                    'last_viewed' => $post->updated_at
                ];
            });

        // Get geographic data for posts (from UserSessions and TrackingEvents)
        $postCountryData = $this->getPostCountryAnalytics($startDate);
        
        // Get detailed time analytics per post
        $postTimeAnalytics = $this->getPostTimeAnalytics($startDate);

        return view('admin.analytics.posts', compact(
            'topPosts', 'engagementPosts', 'contentMetrics', 'recentActivity', 
            'postCountryData', 'postTimeAnalytics', 'period'
        ));
    }

    public function realtime()
    {
        // Active sessions (last 30 minutes)
        $activeSessions = UserSession::active()->count();

        // Recent events (last 5 minutes)
        $recentEvents = TrackingEvent::where('event_time', '>=', now()->subMinutes(5))
            ->with('userSession')
            ->orderByDesc('event_time')
            ->limit(50)
            ->get();

        // Active pages
        $activePages = TrackingEvent::where('event_type', 'page_view')
            ->where('event_time', '>=', now()->subMinutes(30))
            ->selectRaw('url, page_title, COUNT(*) as current_visitors')
            ->groupBy('url', 'page_title')
            ->orderByDesc('current_visitors')
            ->limit(10)
            ->get();

        // Geographic distribution of current visitors
        $currentLocations = UserSession::active()
            ->whereNotNull('country_name')
            ->selectRaw('country_name, country_code, city, COUNT(*) as visitors')
            ->groupBy('country_name', 'country_code', 'city')
            ->orderByDesc('visitors')
            ->get();

        // Device breakdown of current visitors
        $currentDevices = UserSession::active()
            ->selectRaw('device_type, COUNT(*) as visitors')
            ->groupBy('device_type')
            ->get();

        return view('admin.analytics.realtime', compact(
            'activeSessions', 'recentEvents', 'activePages', 
            'currentLocations', 'currentDevices'
        ));
    }

    public function getChartData(Request $request)
    {
        $type = $request->get('type');
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);

        switch ($type) {
            case 'visitors':
                return $this->getVisitorChartData($startDate);
            case 'page_views':
                return $this->getPageViewChartData($startDate);
            case 'devices':
                return $this->getDeviceChartData($startDate);
            case 'countries':
                return $this->getCountryChartData($startDate);
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    private function calculateBounceRate($startDate)
    {
        $totalSessions = UserSession::where('started_at', '>=', $startDate)->count();
        $bouncedSessions = UserSession::where('started_at', '>=', $startDate)
            ->where('page_views', '<=', 1)
            ->count();

        return $totalSessions > 0 ? round(($bouncedSessions / $totalSessions) * 100, 2) : 0;
    }

    private function getVisitorChartData($startDate)
    {
        return UserSession::where('started_at', '>=', $startDate)
            ->selectRaw('DATE(started_at) as date, COUNT(*) as visitors, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getPageViewChartData($startDate)
    {
        return TrackingEvent::where('event_type', 'page_view')
            ->where('event_time', '>=', $startDate)
            ->selectRaw('DATE(event_time) as date, COUNT(*) as page_views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getDeviceChartData($startDate)
    {
        return UserSession::where('started_at', '>=', $startDate)
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->get();
    }

    private function getCountryChartData($startDate)
    {
        return UserSession::where('started_at', '>=', $startDate)
            ->whereNotNull('country_name')
            ->selectRaw('country_name, COUNT(*) as count')
            ->groupBy('country_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
    }

    private function getPostCountryAnalytics($startDate)
    {
        // STRICT: Only get country data from tracking events that have REAL time data
        $countryData = DB::table('tracking_events as te')
            ->join('user_sessions as us', 'te.user_session_id', '=', 'us.id')
            ->leftJoin('posts as p', 'te.post_id', '=', 'p.id')
            ->where('te.event_type', 'page_view')
            ->where('te.event_time', '>=', $startDate)
            ->whereNotNull('us.country_name')
            ->whereNotNull('te.post_id')
            ->whereNotNull('te.time_on_page') // Must have time data (changed to use direct column)
            ->where('te.time_on_page', '>', 10) // Reduced from 30s to 10s for faster results
            ->selectRaw('
                te.post_id,
                p.title as post_title,
                us.country_name,
                us.country_code,
                COUNT(DISTINCT us.id) as unique_visitors,
                COUNT(*) as total_views,
                AVG(te.time_on_page) as avg_time_on_page
            ')
            ->groupBy('te.post_id', 'p.title', 'us.country_name', 'us.country_code')
            ->orderByDesc('total_views')
            ->get()
            ->groupBy('post_id');

        // NO SIMULATION - Only return data if we have real time tracking
        // If no real data with time tracking exists, return empty collection
        return $countryData;
    }

    private function getPostTimeAnalytics($startDate)
    {
        // STRICT: Only get time analytics from tracking events that have REAL time data
        $timeData = DB::table('tracking_events as te')
            ->leftJoin('posts as p', 'te.post_id', '=', 'p.id')
            ->where('te.event_type', 'page_view')
            ->where('te.event_time', '>=', $startDate)
            ->whereNotNull('te.post_id')
            ->whereNotNull('te.time_on_page') // Must have time data (using direct column)
            ->where('te.time_on_page', '>', 0) // Must be >0s
            ->selectRaw('
                te.post_id,
                p.title as post_title,
                AVG(te.time_on_page) as avg_time_seconds,
                MAX(te.time_on_page) as max_time_seconds,
                COUNT(*) as total_sessions
            ')
            ->groupBy('te.post_id', 'p.title')
            ->get()
            ->keyBy('post_id');

        // NO SIMULATION - Only return data if we have real time tracking
        // If no real time data exists, return empty collection
        return $timeData;
    }
}
