<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Contracts\ReportServiceInterface;
use App\Contracts\SnapshotServiceInterface;
use Illuminate\Support\Facades\Log;

/**
 * Class CleanupAnalyticsJob
 * @package App\Jobs
 */
class CleanupAnalyticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    /**
     * Execute the job.
     */
    public function handle(ReportServiceInterface $reportService, SnapshotServiceInterface $snapshotService): void
    {
        $retentionDays = config('analytics.retention_days', 365);

        $reportsCleaned = $reportService->cleanupOldReports($retentionDays);
        $snapshotsCleaned = $snapshotService->pruneSnapshots($retentionDays);

        Log::info("Analytics Cleanup Job Completed: Pruned {$reportsCleaned} reports and {$snapshotsCleaned} snapshots.");
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("CleanupAnalyticsJob Failed: " . $exception->getMessage());
    }
}
