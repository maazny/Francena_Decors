<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\AnalyticsSnapshot;
use App\Enums\MetricType;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface SnapshotServiceInterface
 * @package App\Contracts
 */
interface SnapshotServiceInterface
{
    /**
     * Write snapshot record.
     *
     * @param string $name
     * @param MetricType $type
     * @param string $module
     * @param string $key
     * @param float $value
     * @param array|null $data
     * @param int|null $userId
     * @return AnalyticsSnapshot
     */
    public function captureSnapshot(
        string $name,
        MetricType $type,
        string $module,
        string $key,
        float $value,
        ?array $data = null,
        ?int $userId = null
    ): AnalyticsSnapshot;

    /**
     * Retrieve filtered database snapshots.
     *
     * @param array $filters
     * @return Collection
     */
    public function getSnapshots(array $filters = []): Collection;

    /**
     * Run all counters scanners to cache metrics to database snapshots.
     *
     * @return void
     */
    public function captureSystemSnapshots(): void;

    /**
     * Delete snapshots older than retention settings.
     *
     * @param int $days
     * @return int
     */
    public function pruneSnapshots(int $days): int;
}
