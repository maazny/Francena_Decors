<?php

namespace App\Contracts;

use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface ActivityLogServiceInterface
 * @package App\Contracts
 */
interface ActivityLogServiceInterface
{
    /**
     * Get paginated logs with filter applications.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getLogs(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    /**
     * Find log details by ID.
     *
     * @param int $id
     * @return ActivityLog
     */
    public function getLogById(int $id): ActivityLog;

    /**
     * Search logs globally by keyword.
     *
     * @param string $keyword
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchLogs(string $keyword, int $perPage = 20): LengthAwarePaginator;

    /**
     * Get recent logs.
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecentLogs(int $limit = 10): Collection;

    /**
     * Get logs filtered by User.
     *
     * @param User $user
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUserActivities(User $user, int $perPage = 20): LengthAwarePaginator;

    /**
     * Get logs filtered by Module.
     *
     * @param string $module
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getModuleActivities(string $module, int $perPage = 20): LengthAwarePaginator;

    /**
     * Generate dashboard metrics statistics.
     *
     * @return array
     */
    public function getDashboardStatistics(): array;

    /**
     * Prepare activity logs for export.
     *
     * @param array $filters
     * @return Collection
     */
    public function getExportData(array $filters = []): Collection;

    /**
     * Log a new activity record.
     *
     * @param array $data
     * @return ActivityLog|null
     */
    public function log(array $data): ?ActivityLog;
}
