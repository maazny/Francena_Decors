<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class RecordAnalytics implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // Analytic hooks (e.g. updating database stats logs or external hooks)
        Log::info("Newsletter analytics hook recorded for: " . get_class($event));
    }
}
