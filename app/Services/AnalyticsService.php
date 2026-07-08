<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AnalyticsServiceInterface;
use App\Models\AnalyticsSnapshot;
use App\Traits\InteractsWithAnalytics;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use DateTimeInterface;

/**
 * Class AnalyticsService
 * @package App\Services
 */
class AnalyticsService implements AnalyticsServiceInterface
{
    use InteractsWithAnalytics;

    /**
     * @var ActivityLogService
     */
    protected $activityLogService;

    /**
     * AnalyticsService constructor.
     */
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Get compiled dashboard card metrics.
     */
    public function getDashboardMetrics(): array
    {
        $cacheKey = config('analytics.cache.key') . '_dashboard_kpis';
        $ttl = config('analytics.cache.ttl', 3600);

        if (config('analytics.cache_enabled') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $now = Carbon::now();
        $prevMonth = Carbon::now()->subMonth();

        $metrics = [
            'visitors' => $this->getMetricTrend('website', 'daily_visitors', $prevMonth, $now),
            'blogs' => $this->getMetricTrend('content', 'total_blogs', $prevMonth, $now),
            'projects' => $this->getMetricTrend('content', 'total_projects', $prevMonth, $now),
            'services' => $this->getMetricTrend('content', 'total_services', $prevMonth, $now),
            'media' => $this->getMetricTrend('media', 'media_count', $prevMonth, $now),
            'backups' => $this->getMetricTrend('backup', 'backup_count', $prevMonth, $now),
            'api_requests' => $this->getMetricTrend('api', 'api_requests', $prevMonth, $now),
        ];

        if (config('analytics.cache_enabled')) {
            Cache::put($cacheKey, $metrics, $ttl);
        }

        return $metrics;
    }

    /**
     * Retrieve metric value trend over periods.
     */
    protected function getMetricTrend(string $module, string $key, Carbon $prev, Carbon $current): array
    {
        $currentVal = (float) (AnalyticsSnapshot::where('module', $module)
            ->where('metric_key', $key)
            ->where('captured_at', '>=', $current->subDays(7))
            ->orderBy('captured_at', 'desc')
            ->value('metric_value') ?? 0.0);

        $previousVal = (float) (AnalyticsSnapshot::where('module', $module)
            ->where('metric_key', $key)
            ->whereBetween('captured_at', [$prev->subDays(14), $prev->subDays(7)])
            ->orderBy('captured_at', 'desc')
            ->value('metric_value') ?? 0.0);

        return [
            'value' => $currentVal,
            'previous' => $previousVal,
            'change' => $this->calculatePercentageChange($previousVal, $currentVal),
        ];
    }

    /**
     * Get web logs traffic and overview data.
     */
    public function getWebsiteOverview(DateTimeInterface $start, DateTimeInterface $end): array
    {
        $traffic = AnalyticsSnapshot::where('module', 'website')
            ->where('metric_key', 'daily_visitors')
            ->whereBetween('captured_at', [$start, $end])
            ->orderBy('captured_at', 'asc')
            ->get();

        return [
            'labels' => $traffic->map(fn($s) => Carbon::parse($s->captured_at)->toDateString())->toArray(),
            'values' => $traffic->pluck('metric_value')->toArray(),
        ];
    }

    /**
     * Get server and query performance timing statistics.
     */
    public function getPerformanceMetrics(): array
    {
        $avgDuration = AnalyticsSnapshot::where('module', 'api')
            ->where('metric_key', 'api_avg_duration_ms')
            ->latest('captured_at')
            ->value('metric_value') ?? 0.0;

        return [
            'average_api_response_ms' => $avgDuration,
            'database_connection' => config('database.default'),
        ];
    }

    /**
     * Get compiled activity audits statistics.
     */
    public function getActivityStats(DateTimeInterface $start, DateTimeInterface $end): array
    {
        if (!Schema::hasTable('activity_logs')) {
            return [];
        }

        $activities = DB::table('activity_logs')
            ->select('module', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('module')
            ->get();

        return [
            'labels' => $activities->pluck('module')->toArray(),
            'values' => $activities->pluck('count')->toArray(),
        ];
    }

    /**
     * Get general content counts and distributions.
     */
    public function getContentStats(): array
    {
        return [
            'blogs' => Schema::hasTable('blog_posts') ? DB::table('blog_posts')->count() : 0,
            'projects' => Schema::hasTable('projects') ? DB::table('projects')->count() : 0,
            'services' => Schema::hasTable('services') ? DB::table('services')->count() : 0,
            'testimonials' => Schema::hasTable('testimonials') ? DB::table('testimonials')->count() : 0,
        ];
    }

    /**
     * Get SEO pages configuration metrics.
     */
    public function getSeoStats(): array
    {
        $seoPages = Schema::hasTable('seo_pages') ? DB::table('seo_pages')->count() : 0;
        return [
            'total_pages' => $seoPages,
            'configured_pages' => $seoPages,
        ];
    }

    /**
     * Get media library size and file-type analysis.
     */
    public function getMediaStats(): array
    {
        $mediaCount = Schema::hasTable('media') ? DB::table('media')->count() : 0;
        $mediaSize = Schema::hasTable('media') ? DB::table('media')->sum('size') : 0;

        return [
            'total_files' => $mediaCount,
            'total_size_bytes' => $mediaSize,
            'formatted_size' => round($mediaSize / (1024 * 1024), 2) . ' MB',
        ];
    }

    /**
     * Get REST API status and duration analytics.
     */
    public function getApiStats(DateTimeInterface $start, DateTimeInterface $end): array
    {
        $traffic = AnalyticsSnapshot::where('module', 'api')
            ->where('metric_key', 'api_requests')
            ->whereBetween('captured_at', [$start, $end])
            ->orderBy('captured_at', 'asc')
            ->get();

        return [
            'labels' => $traffic->map(fn($s) => Carbon::parse($s->captured_at)->toDateString())->toArray(),
            'values' => $traffic->pluck('metric_value')->toArray(),
        ];
    }

    /**
     * Get backup storage and logs statistics.
     */
    public function getBackupStats(): array
    {
        $backupCount = Schema::hasTable('backup_histories') ? DB::table('backup_histories')->count() : 0;
        $backupSize = Schema::hasTable('backup_histories') ? DB::table('backup_histories')->sum('size') : 0;

        return [
            'total_backups' => $backupCount,
            'total_size_bytes' => $backupSize,
            'formatted_size' => round($backupSize / (1024 * 1024), 2) . ' MB',
        ];
    }

    /**
     * Get system space usage and queue status flags.
     */
    public function getSystemHealth(): array
    {
        $dbSize = AnalyticsSnapshot::where('module', 'system')
            ->where('metric_key', 'database_size_bytes')
            ->latest('captured_at')
            ->value('metric_value') ?? 0.0;

        return [
            'database_size_bytes' => $dbSize,
            'database_size_formatted' => round((float)$dbSize / (1024 * 1024), 2) . ' MB',
            'cache_driver' => config('cache.default'),
            'queue_connection' => config('queue.default'),
        ];
    }

    /**
     * Compare analytical metrics across two separate periods.
     */
    public function comparePeriods(
        DateTimeInterface $start1,
        DateTimeInterface $end1,
        DateTimeInterface $start2,
        DateTimeInterface $end2,
        string $metric
    ): array {
        $value1 = AnalyticsSnapshot::where('metric_key', $metric)
            ->whereBetween('captured_at', [$start1, $end1])
            ->avg('metric_value') ?? 0.0;

        $value2 = AnalyticsSnapshot::where('metric_key', $metric)
            ->whereBetween('captured_at', [$start2, $end2])
            ->avg('metric_value') ?? 0.0;

        return [
            'period1_avg' => $value1,
            'period2_avg' => $value2,
            'change_percentage' => $this->calculatePercentageChange((float) $value1, (float) $value2),
        ];
    }
}
