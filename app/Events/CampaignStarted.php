<?php

namespace App\Events;

use App\Models\NewsletterCampaign;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignStarted
{
    use Dispatchable, SerializesModels;

    public NewsletterCampaign $campaign;
    public ?int $groupId;

    /**
     * Create a new event instance.
     */
    public function __construct(NewsletterCampaign $campaign, ?int $groupId = null)
    {
        $this->campaign = $campaign;
        $this->groupId = $groupId;
    }
}
