<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserSession;
use App\Models\TrackingEvent;
use App\Models\Post;
use Carbon\Carbon;

class CheckTrackingData extends Command
{
    protected $signature = 'tracking:check {--hours=24 : Number of hours to look back}';
    protected $description = 'Check current tracking data status';

    public function handle()
    {
        $hours = $this->option('hours');
        $since = Carbon::now()->subHours($hours);
        
        $this->info("=== TRACKING DATA CHECK (Last {$hours} hours) ===");
        $this->info("Current time: " . Carbon::now()->setTimezone('America/Lima')->format('Y-m-d H:i:s T'));
        
        // Check sessions
        $sessions = UserSession::where('created_at', '>=', $since)->get();
        $this->info("\n📊 USER SESSIONS: " . $sessions->count());
        
        foreach ($sessions->take(5) as $session) {
            $this->line(sprintf(
                "  - Session %s | %s | %s | Views: %d | Duration: %ds",
                substr($session->session_id, 0, 20) . '...',
                $session->country_name ?? 'Unknown',
                $session->device_type,
                $session->page_views,
                $session->total_duration ?? 0
            ));
        }
        
        // Check tracking events
        $events = TrackingEvent::where('created_at', '>=', $since)->get();
        $this->info("\n📈 TRACKING EVENTS: " . $events->count());
        
        $eventTypes = $events->groupBy('event_type')->map->count();
        foreach ($eventTypes as $type => $count) {
            $this->line("  - {$type}: {$count}");
        }
        
        // Check events with time data
        $eventsWithTime = TrackingEvent::where('created_at', '>=', $since)
            ->whereNotNull('time_on_page')
            ->where('time_on_page', '>', 0)
            ->get();
            
        $this->info("\n⏱️  EVENTS WITH TIME DATA: " . $eventsWithTime->count());
        
        foreach ($eventsWithTime->take(5) as $event) {
            $post = $event->post_id ? Post::find($event->post_id) : null;
            $this->line(sprintf(
                "  - %s | Post: %s | Time: %ds | Type: %s",
                $event->created_at->setTimezone('America/Lima')->format('H:i:s'),
                $post ? substr($post->title, 0, 30) . '...' : 'N/A',
                $event->time_on_page,
                $event->event_type
            ));
        }
        
        // Check events with meaningful time (>30s)
        $meaningfulEvents = TrackingEvent::where('created_at', '>=', $since)
            ->whereNotNull('time_on_page')
            ->where('time_on_page', '>', 30)
            ->get();
            
        $this->info("\n✅ EVENTS WITH >30s TIME: " . $meaningfulEvents->count());
        
        // Check post views
        $postEvents = TrackingEvent::where('created_at', '>=', $since)
            ->where('event_type', 'page_view')
            ->whereNotNull('post_id')
            ->with('post')
            ->get()
            ->groupBy('post_id');
            
        $this->info("\n📖 POSTS VIEWED:");
        foreach ($postEvents as $postId => $events) {
            $post = $events->first()->post;
            if ($post) {
                $this->line(sprintf(
                    "  - %s: %d views",
                    substr($post->title, 0, 50),
                    $events->count()
                ));
            }
        }
        
        // Geographic data with time
        $geoData = TrackingEvent::join('user_sessions', 'tracking_events.user_session_id', '=', 'user_sessions.id')
            ->where('tracking_events.created_at', '>=', $since)
            ->whereNotNull('user_sessions.country_name')
            ->whereNotNull('tracking_events.time_on_page')
            ->where('tracking_events.time_on_page', '>', 30)
            ->selectRaw('user_sessions.country_name, COUNT(*) as count, AVG(tracking_events.time_on_page) as avg_time')
            ->groupBy('user_sessions.country_name')
            ->get();
            
        $this->info("\n🌍 GEOGRAPHIC DATA (with >30s time):");
        if ($geoData->isEmpty()) {
            $this->warn("  No geographic data with meaningful time yet");
        } else {
            foreach ($geoData as $country) {
                $this->line(sprintf(
                    "  - %s: %d events, avg time: %ds",
                    $country->country_name,
                    $country->count,
                    round($country->avg_time)
                ));
            }
        }
        
        $this->info("\n💡 TIPS:");
        $this->line("- Events are sent every 20 seconds while user is active");
        $this->line("- Geographic/Time analytics require >30s on page");
        $this->line("- Make sure to spend more than 30 seconds on a post");
        $this->line("- Check browser console for any JavaScript errors");
        
        return Command::SUCCESS;
    }
}