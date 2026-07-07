<?php

namespace App\Traits;

use App\Contracts\ActivityLogServiceInterface;

trait LogsActivity
{
    /**
     * Helper to log activity using ActivityLogService.
     *
     * @param array $data
     * @return void
     */
    protected function logActivity(array $data): void
    {
        try {
            app(ActivityLogServiceInterface::class)->log($data);
        } catch (\Throwable $e) {
            // Gracefully handle logging exceptions to preserve main workflow execution
            \Illuminate\Support\Facades\Log::error("LogsActivity Trait Error: " . $e->getMessage());
        }
    }
}
