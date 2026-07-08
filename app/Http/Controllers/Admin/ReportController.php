<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Contracts\ReportServiceInterface;
use App\Contracts\SnapshotServiceInterface;
use App\Http\Requests\GenerateReportRequest;
use App\Models\AnalyticsReport;
use App\Enums\ReportType;
use App\Enums\ReportPeriod;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Class ReportController
 * @package App\Http\Controllers\Admin
 */
class ReportController extends Controller
{
    /**
     * @var ReportServiceInterface
     */
    protected $reportService;

    /**
     * @var SnapshotServiceInterface
     */
    protected $snapshotService;

    /**
     * @var ActivityLogService
     */
    protected $activityLogger;

    /**
     * ReportController constructor.
     */
    public function __construct(
        ReportServiceInterface $reportService,
        SnapshotServiceInterface $snapshotService,
        ActivityLogService $activityLogger
    ) {
        $this->reportService = $reportService;
        $this->snapshotService = $snapshotService;
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display report parameters and history list.
     */
    public function index(Request $request)
    {
        $reports = $this->reportService->getReportHistory($request->all());
        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Render the detailed report data view.
     */
    public function show(int $id)
    {
        $report = AnalyticsReport::findOrFail($id);
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Generate an analytical report.
     */
    public function generate(GenerateReportRequest $request)
    {
        $start = Carbon::parse($request->input('start_date'));
        $end = Carbon::parse($request->input('end_date'));

        $report = $this->reportService->generateReport(
            $request->input('report_name'),
            ReportType::from($request->input('report_type')),
            ReportPeriod::from($request->input('period')),
            $start,
            $end,
            auth()->id(),
            $request->input('filters', [])
        );

        return redirect()->route('admin.reports.show', $report->id)
            ->with('success', 'Report generation completed.');
    }

    /**
     * Download the compiled report file from storage.
     */
    public function download(int $id)
    {
        $report = AnalyticsReport::findOrFail($id);
        if (!$report->file_path || !Storage::disk('local')->exists($report->file_path)) {
            return back()->with('error', 'Report file not found.');
        }

        $report->increment('download_count');
        $report->update(['last_downloaded_at' => Carbon::now()]);

        $this->activityLogger->log([
            'user_id' => auth()->id(),
            'module' => 'analytics',
            'action' => ActivityAction::DOWNLOAD,
            'description' => "Downloaded report '{$report->report_name}'",
            'status' => ActivityStatus::SUCCESS,
        ]);

        return Storage::disk('local')->download($report->file_path);
    }

    /**
     * Queue a background job to export the report.
     */
    public function export(Request $request, int $id)
    {
        $request->validate([
            'format' => ['required', 'string', 'in:csv,json'],
        ]);

        $this->reportService->queueReportExport($id, $request->input('format'));

        return response()->json([
            'success' => true,
            'message' => 'Export job queued successfully.'
        ]);
    }

    /**
     * Delete report and file from disk.
     */
    public function destroy(int $id)
    {
        $this->reportService->deleteReport($id);
        return redirect()->route('admin.reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    /**
     * Render the report list log page.
     */
    public function history()
    {
        $reports = AnalyticsReport::latest()->paginate(15);
        return view('admin.reports.history', compact('reports'));
    }

    /**
     * Render snapshot list overview view.
     */
    public function snapshots()
    {
        $snapshots = $this->snapshotService->getSnapshots();
        return view('admin.reports.snapshots', compact('snapshots'));
    }

    /**
     * Render comparisons between periods.
     */
    public function compare(Request $request)
    {
        $request->validate([
            'start1' => ['required', 'date'],
            'end1' => ['required', 'date'],
            'start2' => ['required', 'date'],
            'end2' => ['required', 'date'],
            'metric' => ['required', 'string'],
        ]);

        return view('admin.reports.compare');
    }
}
