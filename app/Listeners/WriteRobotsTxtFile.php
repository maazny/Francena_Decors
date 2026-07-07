<?php

namespace App\Listeners;

use App\Events\RobotsUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class WriteRobotsTxtFile implements ShouldQueue
{
    /**
     * Write updated robots.txt rules to public path statically.
     */
    public function handle(RobotsUpdated $event): void
    {
        try {
            file_put_contents(public_path('robots.txt'), $event->rules);
        } catch (\Throwable $e) {
            Log::error("Failed to write static robots.txt: " . $e->getMessage());
        }
    }
}
