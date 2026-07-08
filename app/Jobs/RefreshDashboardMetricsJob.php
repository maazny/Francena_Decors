<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Contracts\AnalyticsServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Class RefreshDashboardMetricsJob
 * @package App\Jobs
 */
class RefreshDashboardMetricsJob implements ShouldQueue
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
    public $timeout = 120;

    /**
     * Execute the job.
     */
    public function handle(AnalyticsServiceInterface $analyticsService): void
    {
        $cacheKey = config('analytics.cache.key') . '_dashboard_kpis';
        Cache::forget($cacheKey);
        
        $analyticsService->getDashboardMetrics();

        Log::info("Dashboard KPI Cache Refreshed Successfully.");
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("RefreshDashboardMetricsJob Failed: " . $exception->getMessage());
    }
}
