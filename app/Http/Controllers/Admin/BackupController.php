<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBackupRequest;
use App\Http\Requests\RestoreBackupRequest;
use App\Contracts\BackupServiceInterface;
use App\Models\BackupHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class BackupController
 * @package App\Http\Controllers\Admin
 */
class BackupController extends Controller
{
    protected BackupServiceInterface $service;

    /**
     * BackupController constructor.
     *
     * @param BackupServiceInterface $service
     */
    public function __construct(BackupServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of backups.
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $filters = $request->only(['backup_type', 'status', 'storage_disk', 'created_by', 'date_from', 'date_to', 'keyword']);
        $backups = $this->service->getBackupHistory($filters, (int) $request->get('length', 15));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($backups);
        }

        $statistics = $this->service->getStatistics();
        return view('admin.backups.index', compact('backups', 'statistics'));
    }

    /**
     * Display details of a specific backup record.
     *
     * @param int $id
     * @param Request $request
     * @return mixed
     */
    public function show(int $id, Request $request)
    {
        $backup = $this->service->getBackupById($id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($backup);
        }

        return view('admin.backups.show', compact('backup'));
    }

    /**
     * Initialize a new manual backup compiler.
     *
     * @param CreateBackupRequest $request
     * @return JsonResponse
     */
    public function store(CreateBackupRequest $request): JsonResponse
    {
        $data = $request->validated();
        $backup = $this->service->createBackup($data);

        return response()->json([
            'success' => true,
            'message' => "Backup process triggered: {$backup->backup_name}",
            'data' => $backup,
        ]);
    }

    /**
     * Download backup archive securely.
     *
     * @param int $id
     * @return StreamedResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(int $id)
    {
        $backup = $this->service->getBackupById($id);
        $path = $this->service->getDownloadPath($backup);

        return Storage::disk($backup->storage_disk)->download($path, $backup->file_name);
    }

    /**
     * Restore system parameters using a backup archive.
     *
     * @param int $id
     * @param RestoreBackupRequest $request
     * @return JsonResponse
     */
    public function restore(int $id, RestoreBackupRequest $request): JsonResponse
    {
        $backup = $this->service->getBackupById($id);
        $this->service->restoreBackup($backup, auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Restoration process successfully initialized.',
        ]);
    }

    /**
     * Delete backup archive and records.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $backup = $this->service->getBackupById($id);
        $this->service->deleteBackup($backup);

        return response()->json([
            'success' => true,
            'message' => 'Backup record and file deleted successfully.',
        ]);
    }

    /**
     * Run checksum verify on file system.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function verify(int $id): JsonResponse
    {
        $backup = $this->service->getBackupById($id);
        $verified = $this->service->verifyBackup($backup);

        return response()->json([
            'success' => true,
            'verified' => $verified,
            'message' => $verified ? 'Checksum verified successfully.' : 'Integrity checksum failed: archive is corrupted.',
        ]);
    }

    /**
     * Re-trigger compilation of a failed backup.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function retry(int $id): JsonResponse
    {
        $backup = $this->service->getBackupById($id);
        $updatedBackup = $this->service->retryBackup($backup);

        return response()->json([
            'success' => true,
            'message' => 'Retry job successfully dispatched.',
            'data' => $updatedBackup,
        ]);
    }

    /**
     * Retrieve statistics metrics JSON.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        $stats = $this->service->getStatistics();
        return response()->json($stats);
    }

    /**
     * Retrieve historical backups listing data.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function history(Request $request): JsonResponse
    {
        $filters = $request->only(['backup_type', 'status', 'storage_disk', 'created_by', 'date_from', 'date_to', 'keyword']);
        $backups = $this->service->getBackupHistory($filters, (int) $request->get('length', 15));
        return response()->json($backups);
    }

    /**
     * Export backup histories as CSV sheet.
     *
     * @param Request $request
     * @return mixed
     */
    public function export(Request $request)
    {
        $filters = $request->only(['backup_type', 'status', 'storage_disk', 'created_by', 'date_from', 'date_to', 'keyword']);
        $backups = $this->service->getBackupHistory($filters, 5000)->items();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => count($backups),
                'data' => $backups,
            ]);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="backups_history_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($backups) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Backup Name', 'Type', 'Disk', 'Path', 'Size', 'Checksum', 'Status', 'Started At', 'Completed At']);

            foreach ($backups as $backup) {
                fputcsv($file, [
                    $backup->id,
                    $backup->backup_name,
                    $backup->backup_type->value,
                    $backup->storage_disk,
                    $backup->storage_path,
                    $backup->file_size,
                    $backup->checksum,
                    $backup->status->value,
                    $backup->started_at,
                    $backup->completed_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
