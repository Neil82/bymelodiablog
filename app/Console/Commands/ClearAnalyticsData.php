<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\TrackingEvent;
use App\Models\UserSession;
use App\Models\Post;

class ClearAnalyticsData extends Command
{
    protected $signature = 'analytics:clear {--all : Clear all analytics data including post view counters}';
    protected $description = 'Clear all analytics data safely respecting foreign key constraints';

    public function handle()
    {
        $clearAll = $this->option('all');
        
        if ($clearAll) {
            $confirmed = $this->confirm('This will clear ALL analytics data including post view counters. Are you sure?');
        } else {
            $confirmed = $this->confirm('This will clear tracking events and user sessions but keep post view counters. Continue?');
        }
        
        if (!$confirmed) {
            $this->info('Operation cancelled.');
            return;
        }
        
        $this->info('Starting analytics data cleanup...');
        
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            // 1. Clear tracking events first (child table)
            $trackingCount = TrackingEvent::count();
            TrackingEvent::truncate();
            $this->line("✓ Cleared {$trackingCount} tracking events");
            
            // 2. Clear user sessions (parent table)
            $sessionCount = UserSession::count();
            UserSession::truncate();
            $this->line("✓ Cleared {$sessionCount} user sessions");
            
            // 3. Clear post analytics if exists
            if (DB::getSchemaBuilder()->hasTable('post_analytics')) {
                $postAnalyticsCount = DB::table('post_analytics')->count();
                DB::table('post_analytics')->truncate();
                $this->line("✓ Cleared {$postAnalyticsCount} post analytics records");
            }
            
            // 4. Clear visitor analytics if exists
            if (DB::getSchemaBuilder()->hasTable('visitor_analytics')) {
                $visitorAnalyticsCount = DB::table('visitor_analytics')->count();
                DB::table('visitor_analytics')->truncate();
                $this->line("✓ Cleared {$visitorAnalyticsCount} visitor analytics records");
            }
            
            // 5. Reset post view counters if requested
            if ($clearAll) {
                $postsUpdated = Post::where('views', '>', 0)->update(['views' => 0]);
                $this->line("✓ Reset view counters for {$postsUpdated} posts");
            }
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            $this->info("\n🎉 Analytics data cleared successfully!");
            
            if ($clearAll) {
                $this->warn("All analytics data has been cleared. You're starting fresh!");
            } else {
                $this->warn("Tracking data cleared. Post view counters preserved.");
            }
            
            $this->info("\nNext steps:");
            $this->line("1. Visit your site to generate new tracking data");
            $this->line("2. Use 'php artisan tracking:check' to monitor new data");
            $this->line("3. Check analytics dashboard in a few minutes");
            
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            $this->error("Error clearing data: " . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}