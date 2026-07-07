<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityLogFilterRequest;
use App\Contracts\ActivityLogServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class ActivityLogController
 * @package App\Http\Controllers\Admin
 */
class ActivityLogController extends Controller
{
    protected ActivityLogServiceInterface $service;

    /**
     * ActivityLogController constructor.
     *
     * @param ActivityLogServiceInterface $service
     */
    public function __construct(ActivityLogServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $logs = $this->service->getLogs([], (int) $request->get('per_page', 20));
        return response()->json($logs);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $log = $this->service->getLogById($id);
        return response()->json($log);
    }

    /**
     * Search logs globally by keyword query parameter.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $keyword = $request->get('keyword', '');
        $logs = $this->service->searchLogs($keyword, (int) $request->get('per_page', 20));
        return response()->json($logs);
    }

    /**
     * Filter logs using validated constraints.
     *
     * @param ActivityLogFilterRequest $request
     * @return JsonResponse
     */
    public function filter(ActivityLogFilterRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $logs = $this->service->getLogs($filters, (int) $request->get('per_page', 20));
        return response()->json($logs);
    }

    /**
     * Retrieve dashboard audit statistics.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        $stats = $this->service->getDashboardStatistics();
        return response()->json($stats);
    }

    /**
     * Retrieve recent logs feed.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function recent(Request $request): JsonResponse
    {
        $limit = (int) $request->get('limit', 10);
        $logs = $this->service->getRecentLogs($limit);
        return response()->json($logs);
    }

    /**
     * Retrieve activities bound to a specific user.
     *
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     */
    public function userActivities(User $user, Request $request): JsonResponse
    {
        $logs = $this->service->getUserActivities($user, (int) $request->get('per_page', 20));
        return response()->json($logs);
    }

    /**
     * Retrieve activities bound to a specific module name.
     *
     * @param string $module
     * @param Request $request
     * @return JsonResponse
     */
    public function moduleActivities(string $module, Request $request): JsonResponse
    {
        $logs = $this->service->getModuleActivities($module, (int) $request->get('per_page', 20));
        return response()->json($logs);
    }

    /**
     * Export activity logs data foundation stream.
     *
     * @param ActivityLogFilterRequest $request
     * @return JsonResponse
     */
    public function export(ActivityLogFilterRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $logs = $this->service->getExportData($filters);

        $exportType = $filters['export_type'] ?? 'csv';

        // Provide foundation response payload mapping for Part 3 integration
        return response()->json([
            'success' => true,
            'export_type' => $exportType,
            'count' => $logs->count(),
            'data' => $logs,
        ]);
    }
}
