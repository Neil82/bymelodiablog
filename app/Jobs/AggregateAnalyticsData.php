<?php

namespace App\Jobs;

use App\Models\UserSession;
use App\Models\TrackingEvent;
use App\Models\PostAnalytics;
use App\Models\VisitorAnalytics;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AggregateAnalyticsData implements ShouldQueue
{
    use Queueable;

    protected $date;

    /**
     * Create a new job instance.
     */
    public function __construct($date = null)
    {
        $this->date = $date ? Carbon::parse($date) : Carbon::yesterday();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("📊 Starting analytics aggregation for {$this->date->toDateString()}");

        try {
            $this->aggregatePostAnalytics();
            $this->aggregateVisitorAnalytics();
            
            Log::info("✅ Analytics aggregation completed for {$this->date->toDateString()}");
        } catch (\Exception $e) {
            Log::error("❌ Analytics aggregation failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Aggregate post-specific analytics
     */
    private function aggregatePostAnalytics()
    {
        $startDate = $this->date->startOfDay();
        $endDate = $this->date->endOfDay();

        // Get all posts that had views on this date
        $posts = Post::whereHas('analytics', function($query) use ($startDate, $endDate) {
            $query->whereBetween('event_time', [$startDate, $endDate])
                  ->where('event_type', 'page_view');
        })->orWhereHas('trackingEvents', function($query) use ($startDate, $endDate) {
            $query->whereBetween('event_time', [$startDate, $endDate])
                  ->where('event_type', 'page_view');
        })->get();

        foreach ($posts as $post) {
            // Get all sessions that viewed this post on this date
            $postSessions = UserSession::whereHas('trackingEvents', function($query) use ($post, $startDate, $endDate) {
                $query->where('post_id', $post->id)
                      ->where('event_type', 'page_view')
                      ->whereBetween('event_time', [$startDate, $endDate]);
            })->get();

            if ($postSessions->isEmpty()) continue;

            // Calculate metrics
            $uniqueViews = $postSessions->count();
            $totalViews = TrackingEvent::where('post_id', $post->id)
                ->where('event_type', 'page_view')
                ->whereBetween('event_time', [$startDate, $endDate])
                ->count();

            // Get time on page data
            $timeEvents = TrackingEvent::where('post_id', $post->id)
                ->whereIn('event_type', ['page_unload', 'page_hidden'])
                ->whereBetween('event_time', [$startDate, $endDate])
                ->whereNotNull('time_on_page')
                ->get();

            $totalTimeOnPage = $timeEvents->sum('time_on_page');
            $avgTimeOnPage = $timeEvents->count() > 0 ? $timeEvents->avg('time_on_page') : 0;

            // Calculate bounce rate (sessions with only 1 page view)
            $bounceCount = $postSessions->filter(function($session) {
                return $session->page_views <= 1;
            })->count();
            $bounceRate = $uniqueViews > 0 ? round(($bounceCount / $uniqueViews) * 100) : 0;

            // Get scroll depth data
            $scrollEvents = TrackingEvent::where('post_id', $post->id)
                ->where('event_type', 'scroll_milestone')
                ->whereBetween('event_time', [$startDate, $endDate])
                ->whereNotNull('scroll_depth')
                ->get();

            $avgScrollDepth = $scrollEvents->count() > 0 ? $scrollEvents->avg('scroll_depth') : 0;

            // Traffic sources breakdown
            $trafficSources = $postSessions->groupBy(function($session) {
                if (!$session->referrer) return 'direct';
                if (str_contains($session->referrer, 'google')) return 'google';
                if (str_contains($session->referrer, 'facebook')) return 'facebook';
                if (str_contains($session->referrer, 'twitter')) return 'twitter';
                return 'other';
            })->map->count()->toArray();

            // Device breakdown
            $deviceBreakdown = $postSessions->groupBy('device_type')->map->count()->toArray();

            // Country breakdown
            $countryBreakdown = $postSessions->where('country_code', '!=', null)
                ->groupBy('country_code')->map->count()->toArray();

            // Referrer breakdown
            $referrerBreakdown = $postSessions->where('referrer', '!=', null)
                ->groupBy(function($session) {
                    return parse_url($session->referrer, PHP_URL_HOST) ?? 'unknown';
                })->map->count()->toArray();

            // Save or update post analytics
            PostAnalytics::updateOrCreate(
                [
                    'post_id' => $post->id,
                    'date' => $this->date->toDateString()
                ],
                [
                    'unique_views' => $uniqueViews,
                    'total_views' => $totalViews,
                    'avg_time_on_page' => round($avgTimeOnPage),
                    'total_time_on_page' => $totalTimeOnPage,
                    'bounce_rate' => $bounceRate,
                    'scroll_depth_avg' => round($avgScrollDepth),
                    'social_shares' => 0, // To be implemented with social tracking
                    'comments_count' => $post->approvedComments()->whereDate('created_at', $this->date)->count(),
                    'traffic_sources' => $trafficSources,
                    'device_breakdown' => $deviceBreakdown,
                    'country_breakdown' => $countryBreakdown,
                    'referrer_breakdown' => $referrerBreakdown
                ]
            );
        }
    }

    /**
     * Aggregate general visitor analytics
     */
    private function aggregateVisitorAnalytics()
    {
        $startDate = $this->date->startOfDay();
        $endDate = $this->date->endOfDay();

        // Get all sessions for this date
        $sessions = UserSession::whereBetween('started_at', [$startDate, $endDate])
            ->where('is_bot', false)
            ->get();

        if ($sessions->isEmpty()) {
            // Create empty record for the date
            VisitorAnalytics::updateOrCreate(
                ['date' => $this->date->toDateString()],
                [
                    'unique_visitors' => 0,
                    'total_page_views' => 0,
                    'new_visitors' => 0,
                    'returning_visitors' => 0,
                    'avg_session_duration' => 0,
                    'bounce_rate' => 0,
                    'avg_pages_per_session' => 0,
                    'top_pages' => [],
                    'top_referrers' => [],
                    'top_countries' => [],
                    'device_stats' => [],
                    'browser_stats' => [],
                    'os_stats' => [],
                    'traffic_sources' => [],
                    'language_stats' => []
                ]
            );
            return;
        }

        // Basic metrics
        $uniqueVisitors = $sessions->count();
        $uniqueIPs = $sessions->unique('ip_address')->count();
        
        // Page views for this date
        $totalPageViews = TrackingEvent::where('event_type', 'page_view')
            ->whereBetween('event_time', [$startDate, $endDate])
            ->count();

        // New vs returning visitors (based on IP in last 30 days)
        $thirtyDaysAgo = $this->date->copy()->subDays(30);
        $existingIPs = UserSession::whereBetween('started_at', [$thirtyDaysAgo, $startDate])
            ->where('is_bot', false)
            ->pluck('ip_address')
            ->unique();

        $newVisitors = $sessions->whereNotIn('ip_address', $existingIPs)->count();
        $returningVisitors = $uniqueVisitors - $newVisitors;

        // Session duration
        $avgSessionDuration = $sessions->where('total_duration', '>', 0)->avg('total_duration') ?? 0;

        // Bounce rate
        $bouncedSessions = $sessions->where('page_views', '<=', 1)->count();
        $bounceRate = $uniqueVisitors > 0 ? round(($bouncedSessions / $uniqueVisitors) * 100) : 0;

        // Average pages per session
        $avgPagesPerSession = $sessions->avg('page_views') ?? 0;

        // Top pages
        $topPages = TrackingEvent::where('event_type', 'page_view')
            ->whereBetween('event_time', [$startDate, $endDate])
            ->select('url', 'page_title', DB::raw('COUNT(*) as views'))
            ->groupBy('url', 'page_title')
            ->orderByDesc('views')
            ->limit(10)
            ->get()
            ->toArray();

        // Top referrers
        $topReferrers = $sessions->where('referrer', '!=', null)
            ->groupBy(function($session) {
                return parse_url($session->referrer, PHP_URL_HOST) ?? 'unknown';
            })
            ->map->count()
            ->sortDesc()
            ->take(10)
            ->toArray();

        // Top countries
        $topCountries = $sessions->where('country_name', '!=', null)
            ->groupBy('country_name')
            ->map->count()
            ->sortDesc()
            ->take(10)
            ->toArray();

        // Device stats
        $deviceStats = $sessions->groupBy('device_type')->map->count()->toArray();

        // Browser stats
        $browserStats = $sessions->where('browser', '!=', null)
            ->groupBy('browser')
            ->map->count()
            ->sortDesc()
            ->take(10)
            ->toArray();

        // OS stats
        $osStats = $sessions->where('os', '!=', null)
            ->groupBy('os')
            ->map->count()
            ->sortDesc()
            ->take(10)
            ->toArray();

        // Traffic sources
        $trafficSources = $sessions->groupBy(function($session) {
            if (!$session->referrer) return 'direct';
            if (str_contains($session->referrer, 'google')) return 'search';
            if (str_contains($session->referrer, 'facebook') || str_contains($session->referrer, 'twitter') || str_contains($session->referrer, 'instagram')) return 'social';
            return 'referral';
        })->map->count()->toArray();

        // Language stats
        $languageStats = $sessions->where('language_code', '!=', null)
            ->groupBy('language_code')
            ->map->count()
            ->sortDesc()
            ->toArray();

        // Save visitor analytics
        VisitorAnalytics::updateOrCreate(
            ['date' => $this->date->toDateString()],
            [
                'unique_visitors' => $uniqueIPs,
                'total_page_views' => $totalPageViews,
                'new_visitors' => $newVisitors,
                'returning_visitors' => $returningVisitors,
                'avg_session_duration' => round($avgSessionDuration),
                'bounce_rate' => $bounceRate,
                'avg_pages_per_session' => round($avgPagesPerSession, 1),
                'top_pages' => $topPages,
                'top_referrers' => $topReferrers,
                'top_countries' => $topCountries,
                'device_stats' => $deviceStats,
                'browser_stats' => $browserStats,
                'os_stats' => $osStats,
                'traffic_sources' => $trafficSources,
                'language_stats' => $languageStats
            ]
        );
    }
}
