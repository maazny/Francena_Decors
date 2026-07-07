<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBackupScheduleRequest;
use App\Contracts\BackupScheduleServiceInterface;
use App\Models\BackupSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Class BackupScheduleController
 * @package App\Http\Controllers\Admin
 */
class BackupScheduleController extends Controller
{
    protected BackupScheduleServiceInterface $service;

    /**
     * BackupScheduleController constructor.
     *
     * @param BackupScheduleServiceInterface $service
     */
    public function __construct(BackupScheduleServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display listing of configured backup schedules.
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $schedules = $this->service->getSchedules((int) $request->get('length', 15));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($schedules);
        }

        return view('admin.backups.schedules', compact('schedules'));
    }

    /**
     * Create a new backup schedule configuration.
     *
     * @param UpdateBackupScheduleRequest $request
     * @return JsonResponse
     */
    public function store(UpdateBackupScheduleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $schedule = $this->service->createSchedule($data);

        return response()->json([
            'success' => true,
            'message' => 'Backup schedule configured successfully.',
            'data' => $schedule,
        ]);
    }

    /**
     * Update backup schedule configuration.
     *
     * @param int $id
     * @param UpdateBackupScheduleRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateBackupScheduleRequest $request): JsonResponse
    {
        $schedule = BackupSchedule::findOrFail($id);
        $data = $request->validated();
        $updatedSchedule = $this->service->updateSchedule($schedule, $data);

        return response()->json([
            'success' => true,
            'message' => 'Backup schedule updated successfully.',
            'data' => $updatedSchedule,
        ]);
    }

    /**
     * Delete a backup schedule.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $schedule = BackupSchedule::findOrFail($id);
        $this->service->deleteSchedule($schedule);

        return response()->json([
            'success' => true,
            'message' => 'Backup schedule deleted successfully.',
        ]);
    }

    /**
     * Enable backup schedule.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function enable(int $id): JsonResponse
    {
        $schedule = BackupSchedule::findOrFail($id);
        $this->service->enableSchedule($schedule);

        return response()->json([
            'success' => true,
            'message' => 'Backup schedule has been enabled.',
        ]);
    }

    /**
     * Disable backup schedule.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function disable(int $id): JsonResponse
    {
        $schedule = BackupSchedule::findOrFail($id);
        $this->service->disableSchedule($schedule);

        return response()->json([
            'success' => true,
            'message' => 'Backup schedule has been disabled.',
        ]);
    }
}
