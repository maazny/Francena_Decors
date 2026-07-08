<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ReportServiceInterface;
use App\Contracts\AnalyticsServiceInterface;
use App\Models\AnalyticsReport;
use App\Enums\ReportType;
use App\Enums\ReportPeriod;
use App\Enums\ReportStatus;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use App\Services\ActivityLogService;
use App\Jobs\ExportReportJob;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class ReportService
 * @package App\Services
 */
class ReportService implements ReportServiceInterface
{
    /**
     * @var ActivityLogService
     */
    protected $activityLogger;

    /**
     * @var AnalyticsServiceInterface
     */
    protected $analyticsService;

    /**
     * ReportService constructor.
     */
    public function __construct(ActivityLogService $activityLogger, AnalyticsServiceInterface $analyticsService)
    {
        $this->activityLogger = $activityLogger;
        $this->analyticsService = $analyticsService;
    }

    /**
     * Generate an analytical report record and calculate data.
     */
    public function generateReport(
        string $name,
        ReportType $type,
        ReportPeriod $period,
        DateTimeInterface $start,
        DateTimeInterface $end,
        ?int $userId,
        array $filters = []
    ): AnalyticsReport {
        $report = AnalyticsReport::create([
            'report_name' => $name,
            'report_type' => $type,
            'module' => $type->value,
            'period' => $period,
            'start_date' => $start,
            'end_date' => $end,
            'generated_by' => $userId,
            'status' => ReportStatus::PROCESSING,
            'filters' => $filters,
        ]);

        try {
            $data = $this->processReportData($report);
            $report->update([
                'status' => ReportStatus::COMPLETED,
                'total_records' => count($data['items'] ?? []),
                'report_data' => $data,
                'generated_at' => Carbon::now(),
            ]);

            $this->activityLogger->log([
                'user_id' => $userId,
                'module' => 'analytics',
                'action' => ActivityAction::CREATE,
                'description' => "Generated report '{$name}' successfully",
                'status' => ActivityStatus::SUCCESS,
            ]);
        } catch (\Throwable $e) {
            $report->update([
                'status' => ReportStatus::FAILED,
                'notes' => $e->getMessage(),
            ]);

            $this->activityLogger->log([
                'user_id' => $userId,
                'module' => 'analytics',
                'action' => ActivityAction::CREATE,
                'description' => "Report generation failed: " . $e->getMessage(),
                'status' => ActivityStatus::FAILED,
            ]);
        }

        return $report;
    }

    /**
     * Get paginated collection of report records history.
     */
    public function getReportHistory(array $filters = []): LengthAwarePaginator
    {
        $query = AnalyticsReport::query()->with('generator');

        if (!empty($filters['report_type'])) {
            $query->where('report_type', $filters['report_type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(15);
    }

    /**
     * Remove report record and any associated storage files.
     */
    public function deleteReport(int $id): bool
    {
        $report = AnalyticsReport::findOrFail($id);

        if ($report->file_path && Storage::disk('local')->exists($report->file_path)) {
            Storage::disk('local')->delete($report->file_path);
        }

        $report->delete();

        $this->activityLogger->log([
            'user_id' => auth()->id(),
            'module' => 'analytics',
            'action' => ActivityAction::DELETE,
            'description' => "Deleted analytics report '{$report->report_name}'",
            'status' => ActivityStatus::SUCCESS,
        ]);

        return true;
    }

    /**
     * Dispatch background job to export report.
     */
    public function queueReportExport(int $reportId, string $format): void
    {
        ExportReportJob::dispatch($reportId, $format);
    }

    /**
     * Compile statistical report data points based on report configuration type.
     */
    public function processReportData(AnalyticsReport $report): array
    {
        $start = $report->start_date;
        $end = $report->end_date;

        switch ($report->report_type) {
            case ReportType::SYSTEM:
                $items = $this->analyticsService->getSystemHealth();
                break;
            case ReportType::API:
                $items = $this->analyticsService->getApiStats($start, $end);
                break;
            case ReportType::BACKUP:
                $items = $this->analyticsService->getBackupStats();
                break;
            case ReportType::MEDIA:
                $items = $this->analyticsService->getMediaStats();
                break;
            case ReportType::SEO:
                $items = $this->analyticsService->getSeoStats();
                break;
            case ReportType::CONTENT:
                $items = $this->analyticsService->getContentStats();
                break;
            case ReportType::ACTIVITY:
                $items = $this->analyticsService->getActivityStats($start, $end);
                break;
            default:
                $items = $this->analyticsService->getWebsiteOverview($start, $end);
                break;
        }

        return [
            'meta' => [
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'report_type' => $report->report_type->value,
            ],
            'items' => is_array($items) ? $items : [$items],
        ];
    }

    /**
     * Prune report files older than specified days limit.
     */
    public function cleanupOldReports(int $retentionDays): int
    {
        $limit = Carbon::now()->subDays($retentionDays);
        $reports = AnalyticsReport::where('created_at', '<', $limit)->get();

        $count = 0;
        foreach ($reports as $report) {
            if ($report->file_path && Storage::disk('local')->exists($report->file_path)) {
                Storage::disk('local')->delete($report->file_path);
            }
            $report->delete();
            $count++;
        }

        return $count;
    }
}
