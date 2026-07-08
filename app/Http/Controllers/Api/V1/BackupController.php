<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BackupHistory;
use App\Services\BackupService;
use App\Http\Resources\Api\V1\BackupResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BackupController extends ApiController
{
    /**
     * @var BackupService
     */
    protected $backupService;

    /**
     * BackupController constructor.
     */
    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display a listing of system backups.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'backup_type' => $request->backup_type,
            'status' => $request->status,
            'storage_disk' => $request->storage_disk,
            'created_by' => $request->created_by,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'keyword' => $request->search,
        ];

        $perPage = (int) $request->input('per_page', 15);
        $paginator = $this->backupService->getBackupHistory($filters, $perPage);

        return $this->paginatedResponse($paginator, BackupResource::class, 'Backup history retrieved successfully');
    }

    /**
     * Display specific backup details.
     */
    public function show(int $id): JsonResponse
    {
        $backup = $this->backupService->getBackupById($id);
        return $this->success(new BackupResource($backup), 'Backup details retrieved successfully');
    }

    /**
     * Create a manual backup zip archive.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'backup_type' => ['nullable', 'string', 'in:database,files,full'],
            'backup_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['created_by'] = auth()->id() ?: 1;

        $backup = $this->backupService->createBackup($validated);

        return $this->created(new BackupResource($backup), 'Backup process initiated successfully');
    }

    /**
     * Restore system from a backup archive.
     */
    public function restore(int $id): JsonResponse
    {
        $backup = $this->backupService->getBackupById($id);
        $userId = auth()->id() ?: 1;

        try {
            $this->backupService->restoreBackup($backup, $userId);
            return $this->success(null, 'Backup restoration process initiated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    /**
     * Verify backup SHA-256 checksum integrity.
     */
    public function verify(int $id): JsonResponse
    {
        $backup = $this->backupService->getBackupById($id);
        $verified = $this->backupService->verifyBackup($backup);

        return $this->success([
            'is_verified' => $verified,
            'status' => $verified ? 'valid' : 'corrupt',
        ], 'Backup integrity verification completed');
    }

    /**
     * Delete a backup archive.
     */
    public function destroy(int $id): JsonResponse
    {
        $backup = $this->backupService->getBackupById($id);
        $this->backupService->deleteBackup($backup);

        return $this->success(null, 'Backup deleted successfully');
    }
}
