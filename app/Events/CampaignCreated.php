<?php

namespace App\Events;

use App\Models\NewsletterCampaign;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignCreated
{
    use Dispatchable, SerializesModels;

    public NewsletterCampaign $campaign;

    /**
     * Create a new event instance.
     */
    public function __construct(NewsletterCampaign $campaign)
    {
        $this->campaign = $campaign;
    }
}
