<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use App\Models\NewsletterGroup;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterCampaignLog;
use App\Enums\SubscriptionStatus;
use App\Enums\SubscriberSource;
use App\Enums\CampaignStatus;
use App\Mail\NewsletterCampaignMail;
use App\Mail\VerifySubscriptionMail;
use App\Jobs\SendNewsletterCampaignJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NewsletterService
{
    /**
     * Subscribe a user to the newsletter.
     */
    public function subscribe(array $data): NewsletterSubscriber
    {
        $email = $data['email'];
        $doubleOptIn = config('newsletter.double_opt_in', true);
        
        $subscriber = NewsletterSubscriber::withTrashed()->where('email', $email)->first();

        $verificationToken = Str::random(40);
        $unsubscribeToken = Str::random(40);

        if ($subscriber) {
            if ($subscriber->trashed()) {
                $subscriber->restore();
            }
            
            // If already active, just return
            if ($subscriber->status === SubscriptionStatus::ACTIVE) {
                return $subscriber;
            }

            // Update details
            $subscriber->update([
                'name' => $data['name'] ?? $subscriber->name,
                'phone' => $data['phone'] ?? $subscriber->phone,
                'preferred_language' => $data['preferred_language'] ?? $subscriber->preferred_language,
                'status' => $doubleOptIn ? SubscriptionStatus::PENDING : SubscriptionStatus::ACTIVE,
                'verification_status' => !$doubleOptIn,
                'verification_token' => $doubleOptIn ? $verificationToken : null,
                'unsubscribe_token' => $unsubscribeToken,
                'verified_at' => !$doubleOptIn ? Carbon::now() : null,
                'subscribed_at' => !$doubleOptIn ? Carbon::now() : null,
            ]);
        } else {
            $subscriber = NewsletterSubscriber::create([
                'name' => $data['name'] ?? null,
                'email' => $email,
                'phone' => $data['phone'] ?? null,
                'status' => $doubleOptIn ? SubscriptionStatus::PENDING : SubscriptionStatus::ACTIVE,
                'source' => $data['source'] ?? SubscriberSource::WEBSITE,
                'verification_status' => !$doubleOptIn,
                'verification_token' => $doubleOptIn ? $verificationToken : null,
                'unsubscribe_token' => $unsubscribeToken,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'preferred_language' => $data['preferred_language'] ?? 'en',
                'tags' => $data['tags'] ?? [],
                'verified_at' => !$doubleOptIn ? Carbon::now() : null,
                'subscribed_at' => !$doubleOptIn ? Carbon::now() : null,
            ]);
        }

        // Attach to groups if supplied
        if (isset($data['groups']) && is_array($data['groups'])) {
            $subscriber->groups()->sync($data['groups']);
        }

        if ($doubleOptIn) {
            // Send verification email
            try {
                Mail::to($subscriber->email)->send(new VerifySubscriptionMail($subscriber));
            } catch (\Exception $e) {
                Log::error("Failed to send newsletter verification email to {$subscriber->email}: " . $e->getMessage());
            }
        }

        return $subscriber;
    }

    /**
     * Verify a subscription using a verification token.
     */
    public function verify(string $token): ?NewsletterSubscriber
    {
        $subscriber = NewsletterSubscriber::where('verification_token', $token)
            ->where('status', SubscriptionStatus::PENDING)
            ->first();

        if (!$subscriber) {
            return null;
        }

        $subscriber->update([
            'status' => SubscriptionStatus::ACTIVE,
            'verification_status' => true,
            'verification_token' => null,
            'verified_at' => Carbon::now(),
            'subscribed_at' => Carbon::now(),
        ]);

        return $subscriber;
    }

    /**
     * Unsubscribe a user using their unsubscribe token.
     */
    public function unsubscribe(string $token): bool
    {
        return $this->unsubscribeWithReason($token);
    }

    /**
     * Unsubscribe a user using their unsubscribe token with an optional feedback reason.
     */
    public function unsubscribeWithReason(string $token, ?string $reason = null): bool
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return false;
        }

        $currentTags = $subscriber->tags ?: [];
        if ($reason) {
            $currentTags['unsubscribe_reason'] = $reason;
        }

        $subscriber->update([
            'status' => SubscriptionStatus::UNSUBSCRIBED,
            'unsubscribed_at' => Carbon::now(),
            'tags' => $currentTags,
        ]);

        return true;
    }

    /**
     * Update subscriber preferences.
     */
    public function updatePreferences(NewsletterSubscriber $subscriber, array $data): bool
    {
        $updateData = [
            'name' => $data['name'] ?? $subscriber->name,
            'preferred_language' => $data['preferred_language'] ?? $subscriber->preferred_language,
        ];

        // Check email change
        if (isset($data['email']) && $data['email'] !== $subscriber->email) {
            // Validate unique
            $exists = NewsletterSubscriber::where('email', $data['email'])
                ->where('id', '!=', $subscriber->id)
                ->exists();

            if (!$exists) {
                $updateData['email'] = $data['email'];
            }
        }

        // Keep or change status
        if (isset($data['status']) && $data['status'] === 'unsubscribe') {
            $updateData['status'] = SubscriptionStatus::UNSUBSCRIBED;
            $updateData['unsubscribed_at'] = Carbon::now();
        } elseif ($subscriber->status === SubscriptionStatus::UNSUBSCRIBED) {
            $updateData['status'] = SubscriptionStatus::ACTIVE;
            $updateData['subscribed_at'] = Carbon::now();
        }

        $subscriber->update($updateData);

        // Manage group segment subscriptions
        if (isset($data['groups']) && is_array($data['groups'])) {
            $subscriber->groups()->sync($data['groups']);
        } else {
            $subscriber->groups()->detach();
        }

        return true;
    }

    /**
     * Dispatch a campaign sending job.
     */
    public function sendCampaign(NewsletterCampaign $campaign, ?int $groupId = null): void
    {
        $campaign->update([
            'status' => CampaignStatus::SENDING,
        ]);

        // Dispatch background job
        SendNewsletterCampaignJob::dispatch($campaign, $groupId);
    }

    /**
     * Actually send the campaign emails (typically run from the job).
     */
    public function sendCampaignNow(NewsletterCampaign $campaign, ?int $groupId = null): void
    {
        try {
            // Get target subscribers
            $query = NewsletterSubscriber::active();

            if ($groupId) {
                $query->whereHas('groups', function ($q) use ($groupId) {
                    $q->where('newsletter_groups.id', $groupId);
                });
            }

            $subscribers = $query->get();

            if ($subscribers->isEmpty()) {
                $campaign->update([
                    'status' => CampaignStatus::SENT,
                    'sent_at' => Carbon::now(),
                ]);
                return;
            }

            foreach ($subscribers as $subscriber) {
                // Check if already sent to avoid duplicates
                $log = NewsletterCampaignLog::where('campaign_id', $campaign->id)
                    ->where('subscriber_id', $subscriber->id)
                    ->first();

                if ($log && $log->delivery_status === 'sent') {
                    continue;
                }

                if (!$log) {
                    $log = NewsletterCampaignLog::create([
                        'campaign_id' => $campaign->id,
                        'subscriber_id' => $subscriber->id,
                        'delivery_status' => 'pending',
                    ]);
                }

                try {
                    // Send Email
                    Mail::to($subscriber->email)->send(new NewsletterCampaignMail($campaign, $subscriber));

                    $log->update([
                        'delivery_status' => 'sent',
                        'sent_at' => Carbon::now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to send campaign {$campaign->id} to {$subscriber->email}: " . $e->getMessage());

                    $log->update([
                        'delivery_status' => 'failed',
                        'failed' => true,
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }

            $campaign->update([
                'status' => CampaignStatus::SENT,
                'sent_at' => Carbon::now(),
            ]);

        } catch (\Exception $e) {
            Log::error("Error processing campaign {$campaign->id}: " . $e->getMessage());
            $campaign->update([
                'status' => CampaignStatus::CANCELLED,
            ]);
        }
    }
}
