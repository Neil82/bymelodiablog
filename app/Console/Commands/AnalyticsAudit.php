<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\TrackingEvent;
use App\Models\UserSession;
use Illuminate\Support\Facades\DB;

class AnalyticsAudit extends Command
{
    protected $signature = 'analytics:audit {post_id?}';
    protected $description = 'Audit analytics data to find discrepancies';

    public function handle()
    {
        $postId = $this->argument('post_id');
        
        if ($postId) {
            $this->auditSinglePost($postId);
        } else {
            $this->auditTopPosts();
        }
    }
    
    private function auditSinglePost($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            $this->error("Post ID $postId not found");
            return;
        }
        
        $this->info("=== ANALYTICS AUDIT FOR: {$post->title} ===");
        $this->info("Post ID: {$post->id}");
        
        // 1. Basic post data
        $this->info("\n📊 BASIC POST DATA:");
        $this->line("- Views (from posts table): {$post->views}");
        $this->line("- Created: {$post->created_at}");
        $this->line("- Updated: {$post->updated_at}");
        
        // 2. Tracking events data
        $this->info("\n📈 TRACKING EVENTS DATA:");
        
        $pageViews = TrackingEvent::where('post_id', $postId)
            ->where('event_type', 'page_view')
            ->get();
            
        $this->line("- Total page_view events: " . $pageViews->count());
        
        $uniqueSessions = $pageViews->pluck('user_session_id')->unique()->count();
        $this->line("- Unique sessions (visitors): {$uniqueSessions}");
        
        // Time data
        $eventsWithTime = $pageViews->filter(function($event) {
            return $event->time_on_page > 0;
        });
        
        $this->line("- Events with time data: " . $eventsWithTime->count());
        
        if ($eventsWithTime->count() > 0) {
            $avgTime = round($eventsWithTime->avg('time_on_page'));
            $maxTime = $eventsWithTime->max('time_on_page');
            $minTime = $eventsWithTime->min('time_on_page');
            
            $this->line("- Average time: {$avgTime}s");
            $this->line("- Max time: {$maxTime}s");
            $this->line("- Min time: {$minTime}s");
        }
        
        // 3. Session details
        $this->info("\n👥 SESSION DETAILS:");
        $sessions = UserSession::whereIn('id', $pageViews->pluck('user_session_id')->unique())
            ->get();
            
        foreach ($sessions as $session) {
            $sessionEvents = $pageViews->where('user_session_id', $session->id);
            $sessionTime = $sessionEvents->max('time_on_page');
            
            $this->line(sprintf(
                "- Session %s: %s, %s | Events: %d | Max time: %ds",
                substr($session->session_id, 0, 8) . '...',
                $session->country_name ?? 'Unknown',
                $session->device_type,
                $sessionEvents->count(),
                $sessionTime ?? 0
            ));
        }
        
        // 4. Data quality check
        $this->info("\n✅ DATA QUALITY CHECK:");
        
        $discrepancy = abs($post->views - $pageViews->count());
        if ($discrepancy > 0) {
            $this->warn("⚠️  View count mismatch: Post shows {$post->views} but tracking shows {$pageViews->count()} (diff: {$discrepancy})");
        } else {
            $this->line("✓ View counts match");
        }
        
        // 5. What analytics controller would show
        $this->info("\n🖥️  WHAT ANALYTICS CONTROLLER CALCULATES:");
        
        // Simulate the controller logic
        $trackingData = TrackingEvent::where('event_type', 'page_view')
            ->where('post_id', $postId)
            ->selectRaw('
                post_id,
                COUNT(DISTINCT user_session_id) as unique_views,
                COUNT(*) as total_views
            ')
            ->groupBy('post_id')
            ->first();
            
        if ($trackingData) {
            $this->line("- Unique views (from tracking): {$trackingData->unique_views}");
            $this->line("- Total views (from tracking): {$trackingData->total_views}");
        }
        
        // Time analytics
        $timeData = TrackingEvent::where('event_type', 'page_view')
            ->where('post_id', $postId)
            ->whereNotNull('time_on_page')
            ->where('time_on_page', '>', 0)
            ->selectRaw('
                AVG(time_on_page) as avg_time_seconds,
                MAX(time_on_page) as max_time_seconds,
                COUNT(*) as sessions_with_time
            ')
            ->first();
            
        if ($timeData && $timeData->sessions_with_time > 0) {
            $this->line("- Average time (from analytics query): " . round($timeData->avg_time_seconds) . "s");
            $this->line("- Sessions with time data: {$timeData->sessions_with_time}");
        } else {
            $this->line("- No time analytics available (no events with time > 0)");
        }
    }
    
    private function auditTopPosts()
    {
        $this->info("=== TOP 5 POSTS AUDIT ===\n");
        
        $posts = Post::where('views', '>', 0)
            ->orderByDesc('views')
            ->limit(5)
            ->get();
            
        foreach ($posts as $post) {
            $trackingViews = TrackingEvent::where('post_id', $post->id)
                ->where('event_type', 'page_view')
                ->count();
                
            $uniqueVisitors = TrackingEvent::where('post_id', $post->id)
                ->where('event_type', 'page_view')
                ->distinct('user_session_id')
                ->count();
                
            $avgTime = TrackingEvent::where('post_id', $post->id)
                ->where('event_type', 'page_view')
                ->whereNotNull('time_on_page')
                ->where('time_on_page', '>', 0)
                ->avg('time_on_page');
                
            $this->line(sprintf(
                "%s\n  Post views: %d | Tracking views: %d | Unique: %d | Avg time: %ds\n",
                $post->title,
                $post->views,
                $trackingViews,
                $uniqueVisitors,
                round($avgTime ?? 0)
            ));
            
            if ($post->views != $trackingViews) {
                $this->warn("  ⚠️  Mismatch: " . abs($post->views - $trackingViews) . " difference");
            }
        }
        
        $this->info("\n💡 Use 'php artisan analytics:audit {post_id}' for detailed post analysis");
    }
}