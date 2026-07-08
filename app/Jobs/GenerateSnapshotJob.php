<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Contracts\SnapshotServiceInterface;
use Illuminate\Support\Facades\Log;

/**
 * Class GenerateSnapshotJob
 * @package App\Jobs
 */
class GenerateSnapshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 180;

    /**
     * Execute the job.
     */
    public function handle(SnapshotServiceInterface $snapshotService): void
    {
        $snapshotService->captureSystemSnapshots();
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("GenerateSnapshotJob Failed: " . $exception->getMessage());
    }
}
