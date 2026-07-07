<?php

namespace App\Jobs;

use App\Models\BackupHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DeleteBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $disk;
    protected string $path;

    /**
     * Create a new job instance.
     */
    public function __construct(string $disk, string $path)
    {
        $this->disk = $disk;
        $this->path = $path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $storage = Storage::disk($this->disk);
            if ($storage->exists($this->path)) {
                $storage->delete($this->path);
            }
        } catch (\Throwable $e) {
            Log::error("Delete Backup Job Failed for path {$this->path} on disk {$this->disk}: " . $e->getMessage());
        }
    }
}
