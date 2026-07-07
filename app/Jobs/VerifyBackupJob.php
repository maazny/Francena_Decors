<?php

namespace App\Jobs;

use App\Models\BackupHistory;
use App\Contracts\BackupServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $backupHistoryId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $backupHistoryId)
    {
        $this->backupHistoryId = $backupHistoryId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $backup = BackupHistory::find($this->backupHistoryId);
        if (!$backup) {
            return;
        }

        app(BackupServiceInterface::class)->verifyBackup($backup);
    }
}
