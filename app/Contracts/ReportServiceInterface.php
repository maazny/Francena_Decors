<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\AnalyticsReport;
use App\Enums\ReportType;
use App\Enums\ReportPeriod;
use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface ReportServiceInterface
 * @package App\Contracts
 */
interface ReportServiceInterface
{
    /**
     * Generate an analytical report record and calculate data.
     *
     * @param string $name
     * @param ReportType $type
     * @param ReportPeriod $period
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     * @param int|null $userId
     * @param array $filters
     * @return AnalyticsReport
     */
    public function generateReport(
        string $name,
        ReportType $type,
        ReportPeriod $period,
        DateTimeInterface $start,
        DateTimeInterface $end,
        ?int $userId,
        array $filters = []
    ): AnalyticsReport;

    /**
     * Get paginated collection of report records history.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getReportHistory(array $filters = []): LengthAwarePaginator;

    /**
     * Remove report record and any associated storage files.
     *
     * @param int $id
     * @return bool
     */
    public function deleteReport(int $id): bool;

    /**
     * Dispatch background job to export report.
     *
     * @param int $reportId
     * @param string $format
     * @return void
     */
    public function queueReportExport(int $reportId, string $format): void;

    /**
     * Compile statistical report data points based on report configuration type.
     *
     * @param AnalyticsReport $report
     * @return array
     */
    public function processReportData(AnalyticsReport $report): array;

    /**
     * Prune report files older than specified days limit.
     *
     * @param int $retentionDays
     * @return int
     */
    public function cleanupOldReports(int $retentionDays): int;
}
