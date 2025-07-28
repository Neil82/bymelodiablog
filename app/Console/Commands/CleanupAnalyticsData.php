<?php

namespace App\Console\Commands;

use App\Models\UserSession;
use App\Models\TrackingEvent;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupAnalyticsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:cleanup 
                            {--days=90 : Keep data for this many days (default: 90)}
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old analytics data to prevent database bloat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysToKeep = $this->option('days');
        $dryRun = $this->option('dry-run');
        
        $cutoffDate = Carbon::now()->subDays($daysToKeep);
        
        $this->info("🧹 Cleaning up analytics data older than {$daysToKeep} days (before {$cutoffDate->toDateString()})");
        
        if ($dryRun) {
            $this->warn("🔍 DRY RUN MODE - No data will actually be deleted");
        }

        // Count records to be deleted
        $oldSessions = UserSession::where('started_at', '<', $cutoffDate)->count();
        $oldEvents = TrackingEvent::where('event_time', '<', $cutoffDate)->count();

        $this->table(
            ['Table', 'Records to delete'],
            [
                ['user_sessions', number_format($oldSessions)],
                ['tracking_events', number_format($oldEvents)],
                ['Total', number_format($oldSessions + $oldEvents)]
            ]
        );

        if ($oldSessions === 0 && $oldEvents === 0) {
            $this->info("✨ No old data found. Database is clean!");
            return 0;
        }

        if (!$dryRun) {
            if (!$this->confirm("Are you sure you want to delete this data? This action cannot be undone.")) {
                $this->info("Operation cancelled.");
                return 0;
            }

            // Delete old tracking events first (they reference user sessions)
            if ($oldEvents > 0) {
                $this->info("🗑️  Deleting old tracking events...");
                $deletedEvents = TrackingEvent::where('event_time', '<', $cutoffDate)->delete();
                $this->info("✅ Deleted {$deletedEvents} tracking events");
            }

            // Delete old user sessions
            if ($oldSessions > 0) {
                $this->info("🗑️  Deleting old user sessions...");
                $deletedSessions = UserSession::where('started_at', '<', $cutoffDate)->delete();
                $this->info("✅ Deleted {$deletedSessions} user sessions");
            }

            $this->info("🎯 Cleanup completed successfully!");
            
            // Show database size reduction estimate
            $this->info("💡 Tip: Run 'OPTIMIZE TABLE user_sessions, tracking_events;' in MySQL to reclaim disk space.");
        }

        return 0;
    }
}
