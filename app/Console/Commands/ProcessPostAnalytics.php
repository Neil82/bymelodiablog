<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TrackingEvent;
use App\Models\PostAnalytics;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessPostAnalytics extends Command
{
    protected $signature = 'analytics:process-posts {--date=}';
    protected $description = 'Process tracking events to generate post analytics data';

    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::yesterday();
        $dateString = $date->toDateString();
        
        $this->info("Processing post analytics for date: {$dateString}");

        // Get all posts that had page views on this date
        $postViews = TrackingEvent::where('event_type', 'page_view')
            ->whereDate('event_time', $dateString)
            ->whereNotNull('post_id')
            ->select([
                'post_id',
                DB::raw('COUNT(DISTINCT user_session_id) as unique_views'),
                DB::raw('COUNT(*) as total_views'),
                DB::raw('AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(event_data, "$.time_on_page")) AS UNSIGNED)) as avg_time_on_page'),
                DB::raw('SUM(CAST(JSON_UNQUOTE(JSON_EXTRACT(event_data, "$.time_on_page")) AS UNSIGNED)) as total_time_on_page'),
                DB::raw('AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(event_data, "$.scroll_depth")) AS DECIMAL(5,2))) as scroll_depth_avg'),
                DB::raw('MAX(CAST(JSON_UNQUOTE(JSON_EXTRACT(event_data, "$.scroll_depth")) AS DECIMAL(5,2))) as scroll_depth_max')
            ])
            ->groupBy('post_id')
            ->get();

        $this->info("Found " . $postViews->count() . " posts with views on {$dateString}");

        foreach ($postViews as $postView) {
            // Calculate bounce rate for this post on this date
            $totalSessions = TrackingEvent::where('event_type', 'page_view')
                ->whereDate('event_time', $dateString) 
                ->where('post_id', $postView->post_id)
                ->distinct('user_session_id')
                ->count();

            $bouncedSessions = DB::table('user_sessions')
                ->whereDate('started_at', $dateString)
                ->where('page_views', 1)
                ->whereExists(function ($query) use ($postView, $dateString) {
                    $query->select(DB::raw(1))
                        ->from('tracking_events')
                        ->whereColumn('tracking_events.user_session_id', 'user_sessions.id')
                        ->where('tracking_events.event_type', 'page_view')
                        ->where('tracking_events.post_id', $postView->post_id)
                        ->whereDate('tracking_events.event_time', $dateString);
                })
                ->count();

            $bounceRate = $totalSessions > 0 ? round(($bouncedSessions / $totalSessions) * 100, 2) : 0;

            // Create or update post analytics record
            PostAnalytics::updateOrCreate(
                [
                    'post_id' => $postView->post_id,
                    'date' => $dateString
                ],
                [
                    'unique_views' => $postView->unique_views,
                    'total_views' => $postView->total_views,
                    'avg_time_on_page' => round($postView->avg_time_on_page ?? 0),
                    'total_time_on_page' => round($postView->total_time_on_page ?? 0),
                    'bounce_rate' => $bounceRate,
                    'scroll_depth_avg' => round($postView->scroll_depth_avg ?? 0, 2),
                    'scroll_depth_max' => round($postView->scroll_depth_max ?? 0, 2)
                ]
            );

            $this->line("Processed analytics for post ID: {$postView->post_id}");
        }

        // Also process recent dates if no specific date provided
        if (!$this->option('date')) {
            $this->info("Processing last 7 days...");
            for ($i = 2; $i <= 8; $i++) {
                $processDate = Carbon::now()->subDays($i);
                $this->call('analytics:process-posts', ['--date' => $processDate->toDateString()]);
            }
        }

        $this->info("Post analytics processing completed!");
        return 0;
    }
}