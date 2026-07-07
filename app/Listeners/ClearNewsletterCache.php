<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearNewsletterCache
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Log::info("Invalidating newsletter data cache following event: " . get_class($event));

        Cache::forget('newsletter_active_groups');
        Cache::forget('newsletter_templates');
        Cache::forget('newsletter_campaign_stats');
    }
}
