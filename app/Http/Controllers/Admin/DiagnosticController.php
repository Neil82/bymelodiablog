<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\UserSession;
use App\Models\TrackingEvent;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiagnosticController extends Controller
{
    public function index()
    {
        $diagnostics = [
            'database_stats' => $this->getDatabaseStats(),
            'posts_data' => $this->getPostsData(),
            'user_sessions_data' => $this->getUserSessionsData(),
            'tracking_events_data' => $this->getTrackingEventsData(),
            'analytics_queries' => $this->getAnalyticsQueries(),
            'system_info' => $this->getSystemInfo()
        ];

        return view('admin.diagnostics.index', compact('diagnostics'));
    }

    private function getDatabaseStats()
    {
        return [
            'posts_total' => Post::count(),
            'posts_with_views' => Post::where('views', '>', 0)->count(),
            'total_views' => Post::sum('views'),
            'user_sessions_total' => UserSession::count(),
            'tracking_events_total' => TrackingEvent::count(),
            'site_settings_total' => SiteSetting::count(),
        ];
    }

    private function getPostsData()
    {
        return [
            'all_posts' => Post::orderByDesc('views')->get()->map(function($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'views' => $post->views,
                    'published_at' => $post->published_at?->setTimezone('America/Lima')->format('Y-m-d H:i:s T'),
                    'created_at' => $post->created_at->setTimezone('America/Lima')->format('Y-m-d H:i:s T'),
                    'updated_at' => $post->updated_at->setTimezone('America/Lima')->format('Y-m-d H:i:s T'),
                ];
            }),
            'posts_by_views' => Post::selectRaw('
                CASE 
                    WHEN views = 0 THEN "0 views"
                    WHEN views BETWEEN 1 AND 5 THEN "1-5 views"
                    WHEN views BETWEEN 6 AND 10 THEN "6-10 views"
                    WHEN views BETWEEN 11 AND 20 THEN "11-20 views"
                    ELSE "20+ views"
                END as view_range,
                COUNT(*) as post_count
            ')
            ->groupBy('view_range')
            ->get()
        ];
    }

    private function getUserSessionsData()
    {
        $sessions = UserSession::orderByDesc('created_at')->limit(10)->get();
        
        return [
            'total_sessions' => UserSession::count(),
            'sessions_with_country' => UserSession::whereNotNull('country_name')->count(),
            'unique_countries' => UserSession::whereNotNull('country_name')->distinct('country_name')->count(),
            'countries_breakdown' => UserSession::whereNotNull('country_name')
                ->selectRaw('country_name, country_code, COUNT(*) as session_count')
                ->groupBy('country_name', 'country_code')
                ->orderByDesc('session_count')
                ->get(),
            'recent_sessions' => $sessions->map(function($session) {
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address ? substr($session->ip_address, 0, -2) . 'XX' : null,
                    'country_name' => $session->country_name,
                    'country_code' => $session->country_code,
                    'device_type' => $session->device_type,
                    'browser' => $session->browser,
                    'page_views' => $session->page_views,
                    'total_duration' => $session->total_duration,
                    'started_at' => $session->started_at?->setTimezone('America/Lima')->format('Y-m-d H:i:s T'),
                    'last_activity' => $session->last_activity?->setTimezone('America/Lima')->format('Y-m-d H:i:s T'),
                ];
            }),
            'sessions_by_device' => UserSession::selectRaw('device_type, COUNT(*) as count')
                ->groupBy('device_type')
                ->orderByDesc('count')
                ->get(),
        ];
    }

    private function getTrackingEventsData()
    {
        $events = TrackingEvent::orderByDesc('created_at')->limit(10)->get();
        
        return [
            'total_events' => TrackingEvent::count(),
            'events_by_type' => TrackingEvent::selectRaw('event_type, COUNT(*) as count')
                ->groupBy('event_type')
                ->orderByDesc('count')
                ->get(),
            'events_with_post_id' => TrackingEvent::whereNotNull('post_id')->count(),
            'events_with_time_data' => TrackingEvent::whereRaw('JSON_EXTRACT(event_data, "$.time_on_page") IS NOT NULL')->count(),
            'events_with_meaningful_time' => TrackingEvent::whereRaw('CAST(JSON_UNQUOTE(JSON_EXTRACT(event_data, "$.time_on_page")) AS UNSIGNED) > 30')->count(),
            'recent_events' => $events->map(function($event) {
                $timeOnPage = null;
                if ($event->event_data && isset($event->event_data['time_on_page'])) {
                    $timeOnPage = $event->event_data['time_on_page'];
                }
                
                return [
                    'id' => $event->id,
                    'event_type' => $event->event_type,
                    'post_id' => $event->post_id,
                    'user_session_id' => $event->user_session_id,
                    'url' => $event->url,
                    'page_title' => $event->page_title,
                    'time_on_page' => $timeOnPage,
                    'event_time' => $event->event_time?->setTimezone('America/Lima')->format('Y-m-d H:i:s T'),
                    'created_at' => $event->created_at->setTimezone('America/Lima')->format('Y-m-d H:i:s T'),
                ];
            }),
        ];
    }

    private function getAnalyticsQueries()
    {
        // Test the actual analytics queries
        $startDate = now()->subDays(30);
        
        // Geographic query
        $geographicQuery = DB::table('tracking_events as te')
            ->join('user_sessions as us', 'te.user_session_id', '=', 'us.id')
            ->leftJoin('posts as p', 'te.post_id', '=', 'p.id')
            ->where('te.event_type', 'page_view')
            ->where('te.event_time', '>=', $startDate)
            ->whereNotNull('us.country_name')
            ->whereNotNull('te.post_id')
            ->whereRaw('JSON_EXTRACT(te.event_data, "$.time_on_page") IS NOT NULL')
            ->whereRaw('CAST(JSON_UNQUOTE(JSON_EXTRACT(te.event_data, "$.time_on_page")) AS UNSIGNED) > 30')
            ->selectRaw('
                te.post_id,
                p.title as post_title,
                us.country_name,
                us.country_code,
                COUNT(DISTINCT us.id) as unique_visitors,
                COUNT(*) as total_views,
                AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(te.event_data, "$.time_on_page")) AS UNSIGNED)) as avg_time_on_page
            ')
            ->groupBy('te.post_id', 'p.title', 'us.country_name', 'us.country_code')
            ->orderByDesc('total_views')
            ->get();

        // Time analytics query
        $timeQuery = DB::table('tracking_events as te')
            ->leftJoin('posts as p', 'te.post_id', '=', 'p.id')
            ->where('te.event_type', 'page_view')
            ->where('te.event_time', '>=', $startDate)
            ->whereNotNull('te.post_id')
            ->whereRaw('JSON_EXTRACT(te.event_data, "$.time_on_page") IS NOT NULL')
            ->whereRaw('CAST(JSON_UNQUOTE(JSON_EXTRACT(te.event_data, "$.time_on_page")) AS UNSIGNED) > 0')
            ->selectRaw('
                te.post_id,
                p.title as post_title,
                AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(te.event_data, "$.time_on_page")) AS UNSIGNED)) as avg_time_seconds,
                MAX(CAST(JSON_UNQUOTE(JSON_EXTRACT(te.event_data, "$.time_on_page")) AS UNSIGNED)) as max_time_seconds,
                COUNT(*) as total_sessions
            ')
            ->groupBy('te.post_id', 'p.title')
            ->get();

        return [
            'geographic_results_count' => $geographicQuery->count(),
            'geographic_results' => $geographicQuery,
            'time_results_count' => $timeQuery->count(),
            'time_results' => $timeQuery,
        ];
    }

    private function getSystemInfo()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database_connection' => config('database.default'),
            'current_time' => now()->setTimezone('America/Lima')->format('Y-m-d H:i:s T'),
            'timezone' => config('app.timezone'),
            'environment' => app()->environment(),
        ];
    }
}