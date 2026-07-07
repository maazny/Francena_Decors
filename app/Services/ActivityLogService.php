<?php

namespace App\Services;

use App\Contracts\ActivityLogServiceInterface;
use App\Models\User;
use App\Models\ActivityLog;
use App\Jobs\LogActivityJob;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

/**
 * Class ActivityLogService
 * @package App\Services
 */
class ActivityLogService implements ActivityLogServiceInterface
{
    /**
     * Get paginated logs with filter applications.
     */
    public function getLogs(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = ActivityLog::with(['user', 'role'])->latest();

        $query = $this->applyFiltersToQuery($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * Find log details by ID.
     */
    public function getLogById(int $id): ActivityLog
    {
        return ActivityLog::with(['user', 'role'])->findOrFail($id);
    }

    /**
     * Search logs globally by keyword.
     */
    public function searchLogs(string $keyword, int $perPage = 20): LengthAwarePaginator
    {
        $query = ActivityLog::with(['user', 'role'])->latest();

        $query->where(function ($q) use ($keyword) {
            $q->where('description', 'like', "%{$keyword}%")
                ->orWhere('module', 'like', "%{$keyword}%")
                ->orWhere('action', 'like', "%{$keyword}%")
                ->orWhere('ip_address', 'like', "%{$keyword}%")
                ->orWhere('browser', 'like', "%{$keyword}%")
                ->orWhere('device', 'like', "%{$keyword}%")
                ->orWhere('operating_system', 'like', "%{$keyword}%")
                ->orWhere('url', 'like', "%{$keyword}%")
                ->orWhereHas('user', function ($uq) use ($keyword) {
                    $uq->where('name', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%");
                });
        });

        return $query->paginate($perPage);
    }

    /**
     * Get recent logs.
     */
    public function getRecentLogs(int $limit = 10): Collection
    {
        $cacheEnabled = config('activitylog.cache_dashboard', true);
        $duration = config('activitylog.cache_duration', 3600);

        if ($cacheEnabled) {
            return Cache::remember('activity_logs:recent', $duration, function () use ($limit) {
                return ActivityLog::with(['user', 'role'])->latest()->limit($limit)->get();
            });
        }

        return ActivityLog::with(['user', 'role'])->latest()->limit($limit)->get();
    }

    /**
     * Get logs filtered by User.
     */
    public function getUserActivities(User $user, int $perPage = 20): LengthAwarePaginator
    {
        return ActivityLog::with(['user', 'role'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get logs filtered by Module.
     */
    public function getModuleActivities(string $module, int $perPage = 20): LengthAwarePaginator
    {
        return ActivityLog::with(['user', 'role'])
            ->where('module', $module)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Generate dashboard metrics statistics.
     */
    public function getDashboardStatistics(): array
    {
        $cacheEnabled = config('activitylog.cache_dashboard', true);
        $duration = config('activitylog.cache_duration', 3600);

        if ($cacheEnabled) {
            return Cache::remember('activity_logs:statistics', $duration, function () {
                return $this->compileStatistics();
            });
        }

        return $this->compileStatistics();
    }

    /**
     * Prepare activity logs for export.
     */
    public function getExportData(array $filters = []): Collection
    {
        $query = ActivityLog::with(['user', 'role'])->latest();
        $query = $this->applyFiltersToQuery($query, $filters);

        // Limit export to max 5000 records to prevent out-of-memory errors
        return $query->limit(5000)->get();
    }

    /**
     * Log a new activity record.
     */
    public function log(array $data): ?ActivityLog
    {
        if (!config('activitylog.enabled', true)) {
            return null;
        }

        // Gather browser and request metadata
        $meta = $this->gatherRequestMetadata();
        $logData = array_merge($meta, [
            'user_id' => $data['user_id'] ?? (auth()->check() ? auth()->id() : null),
            'role_id' => $data['role_id'] ?? (auth()->check() && auth()->user()->roles()->exists() ? auth()->user()->roles()->first()->id : null),
            'module' => $data['module'] ?? 'system',
            'action' => $data['action'] instanceof ActivityAction ? $data['action'] : ActivityAction::tryFrom($data['action'] ?? 'settings_update') ?? ActivityAction::SETTINGS_UPDATE,
            'model_type' => $data['model_type'] ?? null,
            'model_id' => $data['model_id'] ?? null,
            'description' => $data['description'] ?? null,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'status' => $data['status'] instanceof ActivityStatus ? $data['status'] : ActivityStatus::tryFrom($data['status'] ?? 'success') ?? ActivityStatus::SUCCESS,
        ]);

        if (config('activitylog.queue_logging', false)) {
            LogActivityJob::dispatch($logData);
            $this->clearCache();
            return null;
        }

        $log = ActivityLog::create($logData);
        $this->clearCache();

        return $log;
    }

    /**
     * Compile statistics.
     */
    protected function compileStatistics(): array
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        return [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::where('created_at', '>=', $today)->count(),
            'weekly_logs' => ActivityLog::where('created_at', '>=', $startOfWeek)->count(),
            'monthly_logs' => ActivityLog::where('created_at', '>=', $startOfMonth)->count(),
            'successful_logs' => ActivityLog::where('status', 'success')->count(),
            'failed_logs' => ActivityLog::where('status', 'failed')->count(),
            'most_active_users' => ActivityLog::select('user_id', DB::raw('count(*) as count'))
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->with('user')
                ->get()
                ->toArray(),
            'most_active_modules' => ActivityLog::select('module', DB::raw('count(*) as count'))
                ->groupBy('module')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get()
                ->toArray(),
        ];
    }

    /**
     * Clear caches.
     */
    protected function clearCache(): void
    {
        Cache::forget('activity_logs:recent');
        Cache::forget('activity_logs:statistics');
    }

    /**
     * Apply filter request values to logs queries.
     */
    protected function applyFiltersToQuery($query, array $filters)
    {
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['role_id'])) {
            $query->where('role_id', $filters['role_id']);
        }

        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', Carbon::parse($filters['date_from'])->startOfDay());
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', Carbon::parse($filters['date_to'])->endOfDay());
        }

        if (!empty($filters['ip_address'])) {
            $query->where('ip_address', $filters['ip_address']);
        }

        if (!empty($filters['browser'])) {
            $query->where('browser', 'like', "%{$filters['browser']}%");
        }

        if (!empty($filters['device'])) {
            $query->where('device', $filters['device']);
        }

        if (!empty($filters['operating_system'])) {
            $query->where('operating_system', $filters['operating_system']);
        }

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('description', 'like', "%{$keyword}%")
                  ->orWhere('module', 'like', "%{$keyword}%")
                  ->orWhere('action', 'like', "%{$keyword}%");
            });
        }

        return $query;
    }

    /**
     * Parse and gather metadata from HTTP request.
     */
    protected function gatherRequestMetadata(): array
    {
        $request = request();
        if (!$request) {
            return [];
        }

        $userAgent = $request->header('User-Agent', '');
        $browserMeta = $this->parseUserAgent($userAgent);

        return [
            'ip_address' => $request->ip(),
            'browser' => $browserMeta['browser'],
            'device' => $browserMeta['device'],
            'operating_system' => $browserMeta['os'],
            'user_agent' => $userAgent,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            'request_id' => (string) \Illuminate\Support\Str::uuid(),
        ];
    }

    /**
     * Direct simple regex User-Agent parser.
     */
    protected function parseUserAgent(string $userAgent): array
    {
        $browser = 'Unknown';
        $os = 'Unknown';
        $device = 'Desktop';

        // Detect OS
        if (preg_match('/windows|win32/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $os = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $os = 'iOS';
            $device = preg_match('/ipad/i', $userAgent) ? 'Tablet' : 'Mobile';
        } elseif (preg_match('/android/i', $userAgent)) {
            $os = 'Android';
            $device = 'Mobile';
        }

        // Detect Device
        if ($device === 'Desktop' && preg_match('/mobile|phone|opera mini|iemobile/i', $userAgent)) {
            $device = 'Mobile';
        } elseif ($device === 'Desktop' && preg_match('/tablet|android(?!.*mobile)/i', $userAgent)) {
            $device = 'Tablet';
        }

        // Detect Browser
        if (preg_match('/edg/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            $browser = 'Opera';
        }

        return [
            'browser' => $browser,
            'os' => $os,
            'device' => $device,
        ];
    }
}
