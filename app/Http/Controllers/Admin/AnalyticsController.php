<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Contracts\AnalyticsServiceInterface;
use App\Contracts\SnapshotServiceInterface;
use App\Http\Requests\AnalyticsFilterRequest;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

/**
 * Class AnalyticsController
 * @package App\Http\Controllers\Admin
 */
class AnalyticsController extends Controller
{
    /**
     * @var AnalyticsServiceInterface
     */
    protected $analyticsService;

    /**
     * @var SnapshotServiceInterface
     */
    protected $snapshotService;

    /**
     * @var ActivityLogService
     */
    protected $activityLogger;

    /**
     * AnalyticsController constructor.
     */
    public function __construct(
        AnalyticsServiceInterface $analyticsService,
        SnapshotServiceInterface $snapshotService,
        ActivityLogService $activityLogger
    ) {
        $this->analyticsService = $analyticsService;
        $this->snapshotService = $snapshotService;
        $this->activityLogger = $activityLogger;
    }

    /**
     * Render the core analytics dashboard.
     */
    public function dashboard()
    {
        $this->activityLogger->log([
            'user_id' => auth()->id(),
            'module' => 'analytics',
            'action' => ActivityAction::LOGIN,
            'description' => 'Viewed CMS Analytics Dashboard',
            'status' => ActivityStatus::SUCCESS,
        ]);

        $metrics = $this->analyticsService->getDashboardMetrics();
        return view('admin.analytics.dashboard', compact('metrics'));
    }

    /**
     * Fetch general traffic overview statistics in JSON format.
     */
    public function statistics(AnalyticsFilterRequest $request): JsonResponse
    {
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subMonth();
        $end = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();

        $stats = $this->analyticsService->getWebsiteOverview($start, $end);
        return response()->json($stats);
    }

    /**
     * Render the traffic overview view.
     */
    public function overview()
    {
        return view('admin.analytics.overview');
    }

    /**
     * Render performance timings dashboard.
     */
    public function performance()
    {
        $perf = $this->analyticsService->getPerformanceMetrics();
        return view('admin.analytics.performance', compact('perf'));
    }

    /**
     * Render operations activity audit view.
     */
    public function activity()
    {
        return view('admin.analytics.activity');
    }

    /**
     * Render content metrics summary.
     */
    public function content()
    {
        $stats = $this->analyticsService->getContentStats();
        return view('admin.analytics.content', compact('stats'));
    }

    /**
     * Render SEO parameters summary.
     */
    public function seo()
    {
        $stats = $this->analyticsService->getSeoStats();
        return view('admin.analytics.seo', compact('stats'));
    }

    /**
     * Render media size metrics summary.
     */
    public function media()
    {
        $stats = $this->analyticsService->getMediaStats();
        return view('admin.analytics.media', compact('stats'));
    }

    /**
     * Render REST API requests summary view.
     */
    public function api()
    {
        return view('admin.analytics.api');
    }

    /**
     * Render backup size metrics summary.
     */
    public function backup()
    {
        $stats = $this->analyticsService->getBackupStats();
        return view('admin.analytics.backup', compact('stats'));
    }

    /**
     * Render system health and queue status dashboard.
     */
    public function health()
    {
        $stats = $this->analyticsService->getSystemHealth();
        return view('admin.analytics.health', compact('stats'));
    }
}
