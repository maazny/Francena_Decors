<?php

namespace App\Jobs;

use App\Models\NewsletterCampaign;
use App\Services\NewsletterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewsletterCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public NewsletterCampaign $campaign;
    public ?int $groupId;

    /**
     * Create a new job instance.
     */
    public function __construct(NewsletterCampaign $campaign, ?int $groupId = null)
    {
        $this->campaign = $campaign;
        $this->groupId = $groupId;
    }

    /**
     * Execute the job.
     */
    public function handle(NewsletterService $newsletterService): void
    {
        $newsletterService->sendCampaignNow($this->campaign, $this->groupId);
    }
}
