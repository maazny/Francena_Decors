<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityLogFilterRequest;
use App\Contracts\ActivityLogServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use App\Models\ActivityLog;
use App\Models\Role;

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
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $filters = $request->only([
                'user_id', 'role_id', 'module', 'action', 'status',
                'date_from', 'date_to', 'keyword', 'ip_address',
                'browser', 'device', 'operating_system'
            ]);
            $logs = $this->service->getLogs($filters, (int) $request->get('length', 20));
            return response()->json($logs);
        }

        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $roles = Role::select('id', 'name', 'label')->orderBy('label')->get();
        $statistics = $this->service->getDashboardStatistics();

        // Retrieve list of unique modules historically logged to populate filters
        $modules = ActivityLog::select('module')->distinct()->orderBy('module')->pluck('module')->toArray();
        $actions = \App\Enums\ActivityAction::cases();

        return view('admin.activity-logs.index', compact('users', 'roles', 'statistics', 'modules', 'actions'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        $log = $this->service->getLogById($id);
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($log);
        }
        return view('admin.activity-logs.show', compact('log'));
    }

    /**
     * Print the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function print(int $id)
    {
        $log = $this->service->getLogById($id);
        return view('admin.activity-logs.print', compact('log'));
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
     * @return mixed
     */
    public function export(ActivityLogFilterRequest $request)
    {
        $filters = $request->validated();
        $logs = $this->service->getExportData($filters);

        $exportType = $filters['export_type'] ?? 'csv';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'export_type' => $exportType,
                'count' => $logs->count(),
                'data' => $logs,
            ]);
        }

        // Support direct CSV streaming download for CSV/Excel requests
        if ($exportType === 'csv' || $exportType === 'excel') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="activity_logs_' . date('Ymd_His') . '.csv"',
            ];

            $callback = function () use ($logs) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'UUID', 'User', 'Role', 'Module', 'Action', 'Description', 'IP Address', 'Browser', 'Device', 'OS', 'Status', 'Timestamp']);

                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->uuid,
                        $log->user ? $log->user->name : 'System',
                        $log->role ? $log->role->label : 'N/A',
                        $log->module,
                        $log->action->value,
                        $log->description,
                        $log->ip_address,
                        $log->browser,
                        $log->device,
                        $log->operating_system,
                        $log->status->value,
                        $log->created_at,
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // PDF and Print fallback
        return view('admin.activity-logs.print', ['logs' => $logs, 'is_list' => true]);
    }
}
