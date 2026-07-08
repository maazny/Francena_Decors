<?php

declare(strict_types=1);

namespace App\Contracts;

use DateTimeInterface;

/**
 * Interface AnalyticsServiceInterface
 * @package App\Contracts
 */
interface AnalyticsServiceInterface
{
    /**
     * Get compiled dashboard card metrics.
     *
     * @return array
     */
    public function getDashboardMetrics(): array;

    /**
     * Get web logs traffic and overview data.
     *
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     * @return array
     */
    public function getWebsiteOverview(DateTimeInterface $start, DateTimeInterface $end): array;

    /**
     * Get server and query performance timing statistics.
     *
     * @return array
     */
    public function getPerformanceMetrics(): array;

    /**
     * Get compiled activity audits statistics.
     *
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     * @return array
     */
    public function getActivityStats(DateTimeInterface $start, DateTimeInterface $end): array;

    /**
     * Get general content counts and distributions.
     *
     * @return array
     */
    public function getContentStats(): array;

    /**
     * Get SEO pages configuration metrics.
     *
     * @return array
     */
    public function getSeoStats(): array;

    /**
     * Get media library size and file-type analysis.
     *
     * @return array
     */
    public function getMediaStats(): array;

    /**
     * Get REST API status and duration analytics.
     *
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     * @return array
     */
    public function getApiStats(DateTimeInterface $start, DateTimeInterface $end): array;

    /**
     * Get backup storage and logs statistics.
     *
     * @return array
     */
    public function getBackupStats(): array;

    /**
     * Get system space usage and queue status flags.
     *
     * @return array
     */
    public function getSystemHealth(): array;

    /**
     * Compare analytical metrics across two separate periods.
     *
     * @param DateTimeInterface $start1
     * @param DateTimeInterface $end1
     * @param DateTimeInterface $start2
     * @param DateTimeInterface $end2
     * @param string $metric
     * @return array
     */
    public function comparePeriods(
        DateTimeInterface $start1,
        DateTimeInterface $end1,
        DateTimeInterface $start2,
        DateTimeInterface $end2,
        string $metric
    ): array;
}
