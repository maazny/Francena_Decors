<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RefreshStatistics implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Log::info("Refreshing newsletter dashboard statistics cache due to event: " . get_class($event));

        // Invalidate stats cache keys
        Cache::forget('newsletter_campaign_stats');
        Cache::forget('newsletter_active_groups');
    }
}
