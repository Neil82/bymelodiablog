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

        // Get real-time analytics data from tracking events
        $postAnalytics = TrackingEvent::where('event_type', 'page_view')
            ->whereNotNull('post_id')
            ->where('event_time', '>=', $startDate)
            ->selectRaw('
                post_id,
                COUNT(DISTINCT user_session_id) as unique_views,
                COUNT(*) as total_views,
                AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(event_data, "$.time_on_page")) AS UNSIGNED)) as avg_time_on_page,
                SUM(CAST(JSON_UNQUOTE(JSON_EXTRACT(event_data, "$.time_on_page")) AS UNSIGNED)) as total_time_on_page,
                AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(event_data, "$.scroll_depth")) AS DECIMAL(5,2))) as scroll_depth_avg
            ')
            ->groupBy('post_id')
            ->get()
            ->keyBy('post_id');

        // Calculate bounce rates
        $bounceRates = DB::table('tracking_events as te')
            ->join('user_sessions as us', 'te.user_session_id', '=', 'us.id')
            ->where('te.event_type', 'page_view')
            ->whereNotNull('te.post_id')
            ->where('te.event_time', '>=', $startDate)
            ->where('us.page_views', '=', 1)
            ->selectRaw('te.post_id, COUNT(DISTINCT te.user_session_id) as bounced_sessions')
            ->groupBy('te.post_id')
            ->pluck('bounced_sessions', 'post_id');

        // Get posts with their real-time metrics
        $posts = Post::whereIn('id', $postAnalytics->keys())
            ->get()
            ->map(function($post) use ($postAnalytics, $bounceRates) {
                $analytics = $postAnalytics->get($post->id);
                $bouncedSessions = $bounceRates->get($post->id, 0);
                $bounceRate = $analytics->unique_views > 0 
                    ? round(($bouncedSessions / $analytics->unique_views) * 100, 2) 
                    : 0;
                
                return [
                    'post' => $post,
                    'total_views' => $analytics->total_views,
                    'unique_views' => $analytics->unique_views,
                    'avg_time' => round($analytics->avg_time_on_page ?? 0),
                    'total_time' => round($analytics->total_time_on_page ?? 0),
                    'bounce_rate' => $bounceRate,
                    'scroll_depth_avg' => round($analytics->scroll_depth_avg ?? 0, 2)
                ];
            });

        // Top performing posts
        $topPosts = $posts->sortByDesc('unique_views')->take(20);

        // Posts with longest engagement
        $engagementPosts = $posts->sortByDesc('avg_time')->take(10);

        // Content performance metrics (real-time)
        $contentMetrics = [
            'total_posts_viewed' => $postAnalytics->count(),
            'avg_views_per_post' => $posts->avg('unique_views') ?? 0,
            'avg_time_per_post' => $posts->avg('avg_time') ?? 0,
            'avg_scroll_depth' => $posts->avg('scroll_depth_avg') ?? 0
        ];

        // Get recent activity (last 24 hours)
        $recentActivity = TrackingEvent::where('event_type', 'page_view')
            ->whereNotNull('post_id')
            ->where('event_time', '>=', now()->subHours(24))
            ->with('post')
            ->latest('event_time')
            ->limit(50)
            ->get()
            ->groupBy('post_id')
            ->map(function($events, $postId) {
                return [
                    'post' => $events->first()->post,
                    'views' => $events->count(),
                    'unique_views' => $events->pluck('user_session_id')->unique()->count(),
                    'last_viewed' => $events->first()->event_time
                ];
            })
            ->sortByDesc('views')
            ->take(20);

        return view('admin.analytics.posts', compact(
            'topPosts', 'engagementPosts', 'contentMetrics', 'recentActivity', 'period'
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
}
