<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SnapshotServiceInterface;
use App\Models\AnalyticsSnapshot;
use App\Enums\MetricType;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SnapshotService
 * @package App\Services
 */
class SnapshotService implements SnapshotServiceInterface
{
    /**
     * @var ActivityLogService
     */
    protected $activityLogService;

    /**
     * SnapshotService constructor.
     */
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Write snapshot record.
     */
    public function captureSnapshot(
        string $name,
        MetricType $type,
        string $module,
        string $key,
        float $value,
        ?array $data = null,
        ?int $userId = null
    ): AnalyticsSnapshot {
        $snapshot = AnalyticsSnapshot::create([
            'snapshot_name' => $name,
            'metric_type' => $type,
            'module' => $module,
            'metric_key' => $key,
            'metric_value' => $value,
            'metric_data' => $data,
            'captured_at' => Carbon::now(),
            'created_by' => $userId,
        ]);

        $this->activityLogService->log([
            'user_id' => $userId,
            'module' => 'analytics',
            'action' => ActivityAction::CREATE,
            'description' => "Captured analytics snapshot '{$name}' for metric '{$key}'",
            'status' => ActivityStatus::SUCCESS,
        ]);

        return $snapshot;
    }

    /**
     * Retrieve filtered database snapshots.
     */
    public function getSnapshots(array $filters = []): Collection
    {
        $query = AnalyticsSnapshot::query();

        if (!empty($filters['metric_type'])) {
            $query->where('metric_type', $filters['metric_type']);
        }

        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        if (!empty($filters['metric_key'])) {
            $query->where('metric_key', $filters['metric_key']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('captured_at', [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay()
            ]);
        }

        return $query->latest('captured_at')->get();
    }

    /**
     * Run all counters scanners to cache metrics to database snapshots.
     */
    public function captureSystemSnapshots(): void
    {
        // 1. Visitor Counts (From activity logs distinct IP addresses)
        $visitors = 0;
        if (Schema::hasTable('activity_logs')) {
            $visitors = DB::table('activity_logs')
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->distinct('ip_address')
                ->count('ip_address');
        }
        $this->captureSnapshot('Daily Visitors', MetricType::TRAFFIC, 'website', 'daily_visitors', (float) $visitors);

        // 2. Content counts
        $blogs = Schema::hasTable('blog_posts') ? DB::table('blog_posts')->count() : 0;
        $this->captureSnapshot('Total Blog Posts', MetricType::COUNTER, 'content', 'total_blogs', (float) $blogs);

        $projects = Schema::hasTable('projects') ? DB::table('projects')->count() : 0;
        $this->captureSnapshot('Total Projects', MetricType::COUNTER, 'content', 'total_projects', (float) $projects);

        $services = Schema::hasTable('services') ? DB::table('services')->count() : 0;
        $this->captureSnapshot('Total Services', MetricType::COUNTER, 'content', 'total_services', (float) $services);

        // 3. Media counts
        $mediaCount = Schema::hasTable('media') ? DB::table('media')->count() : 0;
        $mediaSize = Schema::hasTable('media') ? DB::table('media')->sum('size') : 0;
        $this->captureSnapshot('Media Files Count', MetricType::COUNTER, 'media', 'media_count', (float) $mediaCount);
        $this->captureSnapshot('Media Library Size', MetricType::SIZE, 'media', 'media_size_bytes', (float) $mediaSize);

        // 4. API Usage Stats
        $apiRequests = 0;
        $avgDuration = 0.0;
        if (Schema::hasTable('activity_logs')) {
            $apiRequests = DB::table('activity_logs')
                ->where('module', 'api')
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->count();
                
            $avgDuration = DB::table('activity_logs')
                ->where('module', 'api')
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->avg('duration') ?? 0.0;
        }
        $this->captureSnapshot('Daily API Requests', MetricType::TRAFFIC, 'api', 'api_requests', (float) $apiRequests);
        $this->captureSnapshot('API Average Duration', MetricType::DURATION, 'api', 'api_avg_duration_ms', (float) $avgDuration);

        // 5. Backups count
        $backupsCount = Schema::hasTable('backup_histories') ? DB::table('backup_histories')->count() : 0;
        $backupsSize = Schema::hasTable('backup_histories') ? DB::table('backup_histories')->sum('size') : 0;
        $this->captureSnapshot('Total Backup Files', MetricType::COUNTER, 'backup', 'backup_count', (float) $backupsCount);
        $this->captureSnapshot('Backup Storage Size', MetricType::SIZE, 'backup', 'backup_size_bytes', (float) $backupsSize);

        // 6. DB Size
        $dbSize = 0;
        $dbConnection = config('database.default');
        if ($dbConnection === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            if (file_exists($dbPath)) {
                $dbSize = filesize($dbPath);
            }
        } elseif ($dbConnection === 'mysql') {
            $query = "SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema = ?";
            $result = DB::select($query, [config('database.connections.mysql.database')]);
            $dbSize = (int) ($result[0]->size ?? 0);
        }
        $this->captureSnapshot('Database Size', MetricType::SIZE, 'system', 'database_size_bytes', (float) $dbSize);
    }

    /**
     * Delete snapshots older than retention settings.
     */
    public function pruneSnapshots(int $days): int
    {
        $limit = Carbon::now()->subDays($days);
        $count = AnalyticsSnapshot::where('captured_at', '<', $limit)->delete();

        $this->activityLogService->log([
            'user_id' => null,
            'module' => 'analytics',
            'action' => ActivityAction::DELETE,
            'description' => "Pruned {$count} old analytical snapshots captured before {$limit->toDateString()}",
            'status' => ActivityStatus::SUCCESS,
        ]);

        return $count;
    }
}
