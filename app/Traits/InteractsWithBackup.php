<?php

namespace App\Traits;

use App\Contracts\BackupServiceInterface;
use App\Models\BackupHistory;

trait InteractsWithBackup
{
    /**
     * Helper trigger to run backup.
     *
     * @param array $data
     * @return BackupHistory
     */
    protected function triggerBackup(array $data): BackupHistory
    {
        return app(BackupServiceInterface::class)->createBackup($data);
    }

    /**
     * Helper trigger to verify backup.
     *
     * @param BackupHistory $backup
     * @return bool
     */
    protected function triggerVerification(BackupHistory $backup): bool
    {
        return app(BackupServiceInterface::class)->verifyBackup($backup);
    }
}
