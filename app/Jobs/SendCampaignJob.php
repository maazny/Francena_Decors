<?php

namespace App\Jobs;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Models\NewsletterCampaignLog;
use App\Enums\CampaignStatus;
use App\Services\Newsletter\EmailProviderInterface;
use App\Events\CampaignCompleted;
use App\Events\CampaignCancelled;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public NewsletterCampaign $campaign;
    public ?int $groupId;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 30;

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
    public function handle(EmailProviderInterface $emailProvider): void
    {
        try {
            $query = NewsletterSubscriber::active();

            if ($this->groupId) {
                $query->whereHas('groups', function ($q) {
                    $q->where('newsletter_groups.id', $this->groupId);
                });
            }

            // Eager load relationships to prevent N+1 queries
            $subscribers = $query->get();

            if ($subscribers->isEmpty()) {
                $this->campaign->update([
                    'status' => CampaignStatus::SENT,
                    'sent_at' => Carbon::now(),
                ]);
                
                event(new CampaignCompleted($this->campaign));
                return;
            }

            foreach ($subscribers as $subscriber) {
                // Ensure no duplicate deliveries
                $log = NewsletterCampaignLog::where('campaign_id', $this->campaign->id)
                    ->where('subscriber_id', $subscriber->id)
                    ->first();

                if ($log && $log->delivery_status === 'sent') {
                    continue;
                }

                if (!$log) {
                    $log = NewsletterCampaignLog::create([
                        'campaign_id' => $this->campaign->id,
                        'subscriber_id' => $subscriber->id,
                        'delivery_status' => 'pending',
                    ]);
                }

                try {
                    // Personalize template tags
                    $unsubscribeUrl = route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]);
                    
                    $tags = [
                        '{{subscriber_name}}' => $subscriber->name ?: 'Subscriber',
                        '{{name}}' => $subscriber->name ?: 'Subscriber',
                        '[name]' => $subscriber->name ?: 'Subscriber',
                        '{{subscriber_email}}' => $subscriber->email,
                        '{{email}}' => $subscriber->email,
                        '[email]' => $subscriber->email,
                        '{{unsubscribe_url}}' => $unsubscribeUrl,
                        '[unsubscribe_url]' => $unsubscribeUrl,
                    ];

                    $htmlContent = str_replace(array_keys($tags), array_values($tags), $this->campaign->html_content);
                    $plainContent = $this->campaign->plain_text 
                        ? str_replace(array_keys($tags), array_values($tags), $this->campaign->plain_text)
                        : null;

                    // Append unsubscribe footer if missing
                    if (strpos($htmlContent, 'unsubscribe') === false && strpos($htmlContent, $unsubscribeUrl) === false) {
                        $htmlContent .= '<hr><p style="font-size:11px;color:#999;text-align:center;">You received this email because you subscribed to our newsletter. <a href="' . $unsubscribeUrl . '">Unsubscribe here</a>.</p>';
                    }

                    // Dispatch using our swappable provider interface
                    $emailProvider->send(
                        $subscriber->email,
                        $this->campaign->subject,
                        $htmlContent,
                        $this->campaign->sender_name,
                        $this->campaign->sender_email,
                        $plainContent
                    );

                    $log->update([
                        'delivery_status' => 'sent',
                        'sent_at' => Carbon::now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to send campaign ID {$this->campaign->id} to {$subscriber->email}: " . $e->getMessage());

                    $log->update([
                        'delivery_status' => 'failed',
                        'failed' => true,
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }

            $this->campaign->update([
                'status' => CampaignStatus::SENT,
                'sent_at' => Carbon::now(),
            ]);

            event(new CampaignCompleted($this->campaign));

        } catch (\Exception $e) {
            Log::error("Error processing Campaign ID {$this->campaign->id} in queue: " . $e->getMessage());
            
            $this->campaign->update([
                'status' => CampaignStatus::CANCELLED,
            ]);

            event(new CampaignCancelled($this->campaign));
        }
    }
}
