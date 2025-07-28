<?php

namespace App\Console\Commands;

use App\Jobs\AggregateAnalyticsData;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ProcessAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:process 
                            {--date= : Specific date to process (YYYY-MM-DD)}
                            {--days= : Number of days to process from yesterday backwards}
                            {--queue : Queue the job instead of running immediately}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and aggregate analytics data for specified dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date');
        $days = $this->option('days');
        $useQueue = $this->option('queue');

        // Determine dates to process
        $datesToProcess = [];

        if ($date) {
            // Process specific date
            try {
                $datesToProcess[] = Carbon::parse($date);
            } catch (\Exception $e) {
                $this->error("Invalid date format. Use YYYY-MM-DD format.");
                return 1;
            }
        } elseif ($days) {
            // Process multiple days backwards from yesterday
            for ($i = 1; $i <= $days; $i++) {
                $datesToProcess[] = Carbon::now()->subDays($i);
            }
        } else {
            // Default: process yesterday
            $datesToProcess[] = Carbon::yesterday();
        }

        $this->info("📊 Processing analytics for " . count($datesToProcess) . " date(s):");
        foreach ($datesToProcess as $processDate) {
            $this->line("  • " . $processDate->toDateString());
        }

        if ($useQueue) {
            // Queue the jobs
            foreach ($datesToProcess as $processDate) {
                AggregateAnalyticsData::dispatch($processDate->toDateString());
                $this->info("✅ Queued analytics job for {$processDate->toDateString()}");
            }
            $this->info("🎯 All analytics jobs have been queued.");
        } else {
            // Process immediately
            $progressBar = $this->output->createProgressBar(count($datesToProcess));
            $progressBar->start();

            foreach ($datesToProcess as $processDate) {
                try {
                    $job = new AggregateAnalyticsData($processDate->toDateString());
                    $job->handle();
                    $this->newLine();
                    $this->info("✅ Processed analytics for {$processDate->toDateString()}");
                } catch (\Exception $e) {
                    $this->newLine();
                    $this->error("❌ Failed to process {$processDate->toDateString()}: " . $e->getMessage());
                }
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);
            $this->info("🎯 Analytics processing completed!");
        }

        return 0;
    }
}
