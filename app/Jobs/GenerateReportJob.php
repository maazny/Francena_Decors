<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Contracts\ReportServiceInterface;
use App\Models\AnalyticsReport;
use App\Enums\ReportStatus;
use Illuminate\Support\Facades\Log;

/**
 * Class GenerateReportJob
 * @package App\Jobs
 */
class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * @var int
     */
    protected $reportId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $reportId)
    {
        $this->reportId = $reportId;
    }

    /**
     * Execute the job.
     */
    public function handle(ReportServiceInterface $reportService): void
    {
        $report = AnalyticsReport::find($this->reportId);
        if (!$report) {
            return;
        }

        try {
            $data = $reportService->processReportData($report);
            $report->update([
                'status' => ReportStatus::COMPLETED,
                'total_records' => count($data['items'] ?? []),
                'report_data' => $data,
                'generated_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            $report->update([
                'status' => ReportStatus::FAILED,
                'notes' => $exception->getMessage(),
            ]);
            throw $exception;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("GenerateReportJob Failed for Report ID {$this->reportId}: " . $exception->getMessage());
        $report = AnalyticsReport::find($this->reportId);
        if ($report) {
            $report->update([
                'status' => ReportStatus::FAILED,
                'notes' => $exception->getMessage(),
            ]);
        }
    }
}
