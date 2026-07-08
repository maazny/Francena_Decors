<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AnalyticsReport;
use App\Enums\ReportStatus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Class ExportReportJob
 * @package App\Jobs
 */
class ExportReportJob implements ShouldQueue
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
    public $timeout = 180;

    /**
     * @var int
     */
    protected $reportId;

    /**
     * @var string
     */
    protected $format;

    /**
     * Create a new job instance.
     */
    public function __construct(int $reportId, string $format)
    {
        $this->reportId = $reportId;
        $this->format = strtolower($format);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $report = AnalyticsReport::find($this->reportId);
        if (!$report || !$report->report_data) {
            return;
        }

        try {
            $data = $report->report_data;
            $fileName = 'reports/' . $report->uuid . '.' . $this->format;
            
            if ($this->format === 'json') {
                $content = json_encode($data, JSON_PRETTY_PRINT);
            } else {
                $content = "";
                $items = $data['items'] ?? [];
                if (!empty($items)) {
                    $keys = array_keys((array) $items);
                    $content .= implode(',', $keys) . "\n";
                    $content .= implode(',', array_values((array) $items)) . "\n";
                }
            }

            Storage::disk('local')->put($fileName, $content);

            $report->update([
                'file_path' => $fileName,
                'file_type' => $this->format,
                'file_size' => Storage::disk('local')->size($fileName),
                'status' => ReportStatus::COMPLETED,
            ]);
        } catch (\Throwable $e) {
            $report->update([
                'status' => ReportStatus::FAILED,
                'notes' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("ExportReportJob Failed for Report ID {$this->reportId}: " . $exception->getMessage());
        $report = AnalyticsReport::find($this->reportId);
        if ($report) {
            $report->update([
                'status' => ReportStatus::FAILED,
                'notes' => $exception->getMessage(),
            ]);
        }
    }
}
