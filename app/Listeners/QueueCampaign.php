<?php

namespace App\Listeners;

use App\Events\CampaignStarted;
use App\Jobs\SendCampaignJob;
use Illuminate\Support\Facades\Log;

class QueueCampaign
{
    /**
     * Handle the event.
     */
    public function handle(CampaignStarted $event): void
    {
        $campaign = $event->campaign;
        $groupId = $event->groupId;

        Log::info("Campaign dispatch listener triggered for Campaign ID {$campaign->id}, Group ID: " . ($groupId ?: 'All'));

        // Dispatch background processing job
        SendCampaignJob::dispatch($campaign, $groupId);
    }
}
