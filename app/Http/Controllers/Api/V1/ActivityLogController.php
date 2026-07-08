<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\ActivityLogService;
use App\Http\Resources\Api\V1\ActivityLogResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends ApiController
{
    /**
     * @var ActivityLogService
     */
    protected $activityLogService;

    /**
     * ActivityLogController constructor.
     */
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display a listing of system activity logs using service query filters.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
            'module' => $request->module,
            'action' => $request->action,
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'ip_address' => $request->ip_address,
            'browser' => $request->browser,
            'device' => $request->device,
            'operating_system' => $request->operating_system,
        ];

        $perPage = (int) $request->input('per_page', 20);

        if ($request->filled('search')) {
            $paginator = $this->activityLogService->searchLogs($request->search, $perPage);
        } else {
            $paginator = $this->activityLogService->getLogs($filters, $perPage);
        }

        return $this->paginatedResponse($paginator, ActivityLogResource::class, 'Activity logs retrieved successfully');
    }

    /**
     * Display specific activity log details.
     */
    public function show(int $id): JsonResponse
    {
        $log = $this->activityLogService->getLogById($id);
        return $this->success(new ActivityLogResource($log), 'Activity log details retrieved successfully');
    }
}
