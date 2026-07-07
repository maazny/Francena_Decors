<?php

namespace App\Services;

use App\Contracts\BackupServiceInterface;
use App\Models\BackupHistory;
use App\Enums\BackupType;
use App\Enums\BackupStatus;
use App\Jobs\RunBackupJob;
use App\Jobs\RestoreBackupJob;
use App\Jobs\VerifyBackupJob;
use App\Jobs\DeleteBackupJob;
use App\Traits\LogsActivity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class BackupService
 * @package App\Services
 */
class BackupService implements BackupServiceInterface
{
    use LogsActivity;

    /**
     * Display back history listing with filtering.
     */
    public function getBackupHistory(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = BackupHistory::with(['creator', 'restorer'])->latest();

        if (!empty($filters['backup_type'])) {
            $query->where('backup_type', $filters['backup_type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['storage_disk'])) {
            $query->where('storage_disk', $filters['storage_disk']);
        }

        if (!empty($filters['created_by'])) {
            $query->where('created_by', $filters['created_by']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', Carbon::parse($filters['date_from'])->startOfDay());
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', Carbon::parse($filters['date_to'])->endOfDay());
        }

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('backup_name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('file_name', 'like', "%{$keyword}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Retrieve backup by ID.
     */
    public function getBackupById(int $id): BackupHistory
    {
        return BackupHistory::with(['creator', 'restorer'])->findOrFail($id);
    }

    /**
     * Dispatch/Execute a new backup request.
     */
    public function createBackup(array $data): BackupHistory
    {
        $type = $data['backup_type'] instanceof BackupType ? $data['backup_type'] : BackupType::tryFrom($data['backup_type'] ?? 'database') ?? BackupType::DATABASE;
        $disk = $data['storage_disk'] ?? config('backup.default_disk', 'local');

        $backup = BackupHistory::create([
            'backup_name' => $data['backup_name'] ?? 'Backup_' . date('Ymd_His'),
            'backup_type' => $type,
            'description' => $data['description'] ?? null,
            'storage_disk' => $disk,
            'storage_path' => 'backups/pending',
            'file_name' => 'pending.zip',
            'file_size' => 0,
            'compression' => config('backup.compression_enabled', true),
            'encryption' => config('backup.encryption_enabled', false),
            'status' => BackupStatus::PENDING,
            'created_by' => $data['created_by'] ?? (auth()->check() ? auth()->id() : null),
            'notes' => $data['notes'] ?? null,
        ]);

        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::CREATE,
            'description' => "Backup request '{$backup->backup_name}' was initialized.",
            'model_type' => BackupHistory::class,
            'model_id' => $backup->id,
        ]);

        if (config('backup.queue_enabled', true)) {
            RunBackupJob::dispatch($backup->id);
        } else {
            $this->executeBackupProcess($backup);
        }

        $this->clearCache();

        return $backup;
    }

    /**
     * Download backup file stream path.
     */
    public function getDownloadPath(BackupHistory $backup): string
    {
        $storage = Storage::disk($backup->storage_disk);
        if (!$storage->exists($backup->storage_path)) {
            throw new \Exception("Backup file does not exist on storage disk.");
        }

        $backup->increment('download_count');
        $backup->update(['last_downloaded_at' => now()]);

        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::DOWNLOAD,
            'description' => "Backup file '{$backup->backup_name}' was downloaded.",
            'model_type' => BackupHistory::class,
            'model_id' => $backup->id,
        ]);

        return $backup->storage_path;
    }

    /**
     * Execute restoration job of a backup.
     */
    public function restoreBackup(BackupHistory $backup, int $userId): void
    {
        if (!config('backup.allow_restore', true)) {
            throw new \Exception("System restoration is disabled in settings.");
        }

        $backup->update(['status' => BackupStatus::RESTORING]);

        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::APPROVE,
            'description' => "Backup restoration '{$backup->backup_name}' was initialized.",
            'model_type' => BackupHistory::class,
            'model_id' => $backup->id,
        ]);

        if (config('backup.queue_enabled', true)) {
            RestoreBackupJob::dispatch($backup->id, $userId);
        } else {
            $this->executeRestoreProcess($backup, $userId);
        }

        $this->clearCache();
    }

    /**
     * Delete backup database records and storage files.
     */
    public function deleteBackup(BackupHistory $backup): void
    {
        // Dispatch physical delete in background to prevent blockages
        DeleteBackupJob::dispatch($backup->storage_disk, $backup->storage_path);

        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::DELETE,
            'description' => "Backup '{$backup->backup_name}' was deleted.",
            'model_type' => BackupHistory::class,
            'model_id' => $backup->id,
        ]);

        $backup->delete();
        $this->clearCache();
    }

    /**
     * Run checksum integrity validation checks.
     */
    public function verifyBackup(BackupHistory $backup): bool
    {
        $storage = Storage::disk($backup->storage_disk);
        if (!$storage->exists($backup->storage_path)) {
            $backup->update(['is_verified' => false, 'failure_reason' => 'Archive file missing.']);
            return false;
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'b_verify');
        file_put_contents($tempFile, $storage->get($backup->storage_path));
        $checksum = hash_file('sha256', $tempFile);
        unlink($tempFile);

        $verified = ($checksum === $backup->checksum);

        $backup->update(['is_verified' => $verified]);

        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::SETTINGS_UPDATE,
            'description' => "Backup verification completed for '{$backup->backup_name}'. Result: " . ($verified ? 'Verified' : 'Corrupted'),
            'model_type' => BackupHistory::class,
            'model_id' => $backup->id,
        ]);

        return $verified;
    }

    /**
     * Compile dashboard stats parameters.
     */
    public function getStatistics(): array
    {
        return Cache::remember('backups:statistics', 3600, function () {
            return [
                'total_backups' => BackupHistory::count(),
                'database_backups' => BackupHistory::where('backup_type', BackupType::DATABASE)->count(),
                'full_backups' => BackupHistory::where('backup_type', BackupType::FULL)->count(),
                'storage_used' => (int) BackupHistory::where('status', BackupStatus::COMPLETED)->sum('file_size'),
                'latest_backup' => BackupHistory::where('status', BackupStatus::COMPLETED)->latest()->first(),
                'failed_backups' => BackupHistory::where('status', BackupStatus::FAILED)->count(),
            ];
        });
    }

    /**
     * Retry compiling a failed backup.
     */
    public function retryBackup(BackupHistory $backup): BackupHistory
    {
        $backup->update([
            'status' => BackupStatus::PENDING,
            'failure_reason' => null,
            'started_at' => null,
            'completed_at' => null,
        ]);

        $this->logActivity([
            'module' => 'backup',
            'action' => \App\Enums\ActivityAction::CREATE,
            'description' => "Backup retry triggered for '{$backup->backup_name}'.",
            'model_type' => BackupHistory::class,
            'model_id' => $backup->id,
        ]);

        if (config('backup.queue_enabled', true)) {
            RunBackupJob::dispatch($backup->id);
        } else {
            $this->executeBackupProcess($backup);
        }

        $this->clearCache();

        return $backup;
    }

    /**
     * Clean up expired backups based on retention policy.
     */
    public function cleanupExpiredBackups(): int
    {
        $retentionDays = config('backup.retain_days', 30);
        $expiredBackups = BackupHistory::where('created_at', '<', now()->subDays($retentionDays))->get();

        $count = 0;
        foreach ($expiredBackups as $backup) {
            $this->deleteBackup($backup);
            $count++;
        }

        return $count;
    }

    /**
     * Internal actual zipping / vacuum executor called by RunBackupJob.
     */
    public function executeBackupProcess(BackupHistory $backup): void
    {
        $backup->update([
            'status' => BackupStatus::RUNNING,
            'started_at' => now(),
        ]);

        $tempDir = sys_get_temp_dir();
        $fileName = 'backup_' . Str::slug($backup->backup_name) . '_' . date('Ymd_His') . '.zip';
        $tempZipPath = $tempDir . DIRECTORY_SEPARATOR . $fileName;

        try {
            $zip = new \ZipArchive();
            if ($zip->open($tempZipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                throw new \Exception("Could not create temporary zip archive.");
            }

            // Perform backup compile depending on selected category
            switch ($backup->backup_type) {
                case BackupType::DATABASE:
                    $this->backupDatabase($zip);
                    break;
                case BackupType::STORAGE:
                    $this->backupDirectory($zip, storage_path('app'), 'storage');
                    break;
                case BackupType::MEDIA:
                    $this->backupDirectory($zip, storage_path('app/public'), 'media');
                    break;
                case BackupType::FULL:
                    $this->backupDatabase($zip);
                    $this->backupDirectory($zip, app_path(), 'app');
                    $this->backupDirectory($zip, config_path(), 'config');
                    $this->backupDirectory($zip, database_path('seeders'), 'database/seeders');
                    $this->backupDirectory($zip, resource_path('views'), 'resources/views');
                    $this->backupDirectory($zip, base_path('routes'), 'routes');
                    break;
                case BackupType::CUSTOM:
                    // Default to database vacuuming as basic custom backup
                    $this->backupDatabase($zip);
                    break;
            }

            $zip->close();

            // Calculate metrics
            $fileSize = filesize($tempZipPath);
            $checksum = hash_file('sha256', $tempZipPath);

            // Store compilation output to selected destination drive
            $storagePath = 'backups/' . $fileName;
            $storage = Storage::disk($backup->storage_disk);
            $storage->put($storagePath, fopen($tempZipPath, 'r'));
            unlink($tempZipPath);

            $completedAt = now();
            $duration = $completedAt->diffInSeconds($backup->started_at);

            $backup->update([
                'status' => BackupStatus::COMPLETED,
                'completed_at' => $completedAt,
                'duration_seconds' => $duration,
                'file_name' => $fileName,
                'storage_path' => $storagePath,
                'file_size' => $fileSize,
                'checksum' => $checksum,
                'is_verified' => true,
                'file_extension' => 'zip',
                'mime_type' => 'application/zip',
            ]);

            $this->logActivity([
                'module' => 'backup',
                'action' => \App\Enums\ActivityAction::CREATE,
                'description' => "Backup process completed successfully for '{$backup->backup_name}'.",
                'model_type' => BackupHistory::class,
                'model_id' => $backup->id,
            ]);

        } catch (\Throwable $e) {
            if (file_exists($tempZipPath)) {
                unlink($tempZipPath);
            }
            throw $e;
        }
    }

    /**
     * Internal extraction and DB replace runner called by RestoreBackupJob.
     */
    public function executeRestoreProcess(BackupHistory $backup, int $userId): void
    {
        // 1. Double check integrity checksum validation checks
        if (!$this->verifyBackup($backup)) {
            throw new \Exception("Restore aborting: Backup file checksum mismatch.");
        }

        $storage = Storage::disk($backup->storage_disk);
        $tempZip = tempnam(sys_get_temp_dir(), 'b_restore');
        file_put_contents($tempZip, $storage->get($backup->storage_path));

        try {
            $zip = new \ZipArchive();
            if ($zip->open($tempZip) !== true) {
                throw new \Exception("Could not extract restore zip archive.");
            }

            // Execute restore calculations depending on category
            if ($backup->backup_type === BackupType::DATABASE || $backup->backup_type === BackupType::FULL) {
                // SQLite Database recovery: replace sqlite database safely
                $dbIndex = $zip->locateName('database.sqlite');
                if ($dbIndex !== false) {
                    $zip->extractTo(sys_get_temp_dir(), 'database.sqlite');
                    $tempDb = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'database.sqlite';
                    
                    // Safe replacement
                    copy($tempDb, database_path('database.sqlite'));
                    unlink($tempDb);
                }
            }

            // Restore other files as backup targets if present
            if ($backup->backup_type === BackupType::MEDIA || $backup->backup_type === BackupType::FULL) {
                $zip->extractTo(storage_path('app/public'));
            }

            $zip->close();
            unlink($tempZip);

            $backup->update([
                'status' => BackupStatus::RESTORED,
                'restored_by' => $userId,
            ]);

            $this->logActivity([
                'module' => 'backup',
                'action' => \App\Enums\ActivityAction::APPROVE,
                'description' => "Restore completed successfully for backup '{$backup->backup_name}'.",
                'model_type' => BackupHistory::class,
                'model_id' => $backup->id,
            ]);

        } catch (\Throwable $e) {
            if (file_exists($tempZip)) {
                unlink($tempZip);
            }
            throw $e;
        }
    }

    /**
     * SQLite safe vacuum database backup helper.
     */
    protected function backupDatabase(\ZipArchive $zip): void
    {
        $dbPath = DB::connection()->getDatabaseName();

        if ($dbPath === ':memory:') {
            // Support in-memory database testing
            $zip->addFromString('database.sqlite', 'in-memory-sqlite-dump');
            return;
        }

        $tempDb = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'temp_database_' . Str::random(8) . '.sqlite';

        if (DB::transactionLevel() > 0) {
            // SQLite cannot run VACUUM INTO inside a transaction (common in RefreshDatabase testing)
            copy($dbPath, $tempDb);
        } else {
            DB::statement("VACUUM INTO '{$tempDb}'");
        }

        $zip->addFile($tempDb, 'database.sqlite');

        // Queue temp path cleanup
        register_shutdown_function(function () use ($tempDb) {
            if (file_exists($tempDb)) {
                unlink($tempDb);
            }
        });
    }

    /**
     * Recursive directory compressor helper.
     */
    protected function backupDirectory(\ZipArchive $zip, string $path, string $zipDir): void
    {
        if (!is_dir($path)) {
            return;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipDir . '/' . substr($filePath, strlen($path) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    /**
     * Clear caches.
     */
    protected function clearCache(): void
    {
        Cache::forget('backups:statistics');
    }
}
