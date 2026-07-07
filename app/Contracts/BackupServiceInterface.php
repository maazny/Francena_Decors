<?php

namespace App\Contracts;

use App\Models\BackupHistory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface BackupServiceInterface
 * @package App\Contracts
 */
interface BackupServiceInterface
{
    /**
     * Display back history listing with filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getBackupHistory(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Retrieve backup by ID.
     *
     * @param int $id
     * @return BackupHistory
     */
    public function getBackupById(int $id): BackupHistory;

    /**
     * Dispatch/Execute a new backup request.
     *
     * @param array $data
     * @return BackupHistory
     */
    public function createBackup(array $data): BackupHistory;

    /**
     * Download backup file stream path.
     *
     * @param BackupHistory $backup
     * @return string
     */
    public function getDownloadPath(BackupHistory $backup): string;

    /**
     * Execute restoration job of a backup.
     *
     * @param BackupHistory $backup
     * @param int $userId
     * @return void
     */
    public function restoreBackup(BackupHistory $backup, int $userId): void;

    /**
     * Delete backup database records and storage files.
     *
     * @param BackupHistory $backup
     * @return void
     */
    public function deleteBackup(BackupHistory $backup): void;

    /**
     * Run checksum integrity validation checks.
     *
     * @param BackupHistory $backup
     * @return bool
     */
    public function verifyBackup(BackupHistory $backup): bool;

    /**
     * Compile dashboard stats parameters.
     *
     * @return array
     */
    public function getStatistics(): array;

    /**
     * Retry compiling a failed backup.
     *
     * @param BackupHistory $backup
     * @return BackupHistory
     */
    public function retryBackup(BackupHistory $backup): BackupHistory;

    /**
     * Clean up expired backups based on retention policy.
     *
     * @return int Number of deleted backups
     */
    public function cleanupExpiredBackups(): int;
}
