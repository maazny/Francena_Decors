<?php

namespace App\Jobs;

use App\Models\BackupHistory;
use App\Contracts\BackupServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunBackupJob implements ShouldQueue
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

        try {
            // Retrieve Service using app() to allow runtime dependency injection
            $service = app(BackupServiceInterface::class);
            
            // Execute actual file compilation
            if (method_exists($service, 'executeBackupProcess')) {
                $service->executeBackupProcess($backup);
            }
        } catch (\Throwable $e) {
            Log::error("Backup Job Failed for ID {$this->backupHistoryId}: " . $e->getMessage());
            
            if ($backup) {
                $backup->update([
                    'status' => \App\Enums\BackupStatus::FAILED,
                    'failure_reason' => $e->getMessage(),
                    'completed_at' => now(),
                ]);
            }
        }
    }
}
