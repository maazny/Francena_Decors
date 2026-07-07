<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use App\Models\NewsletterGroup;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterCampaignLog;
use App\Models\NewsletterCampaignTemplate;
use App\Enums\SubscriptionStatus;
use App\Enums\SubscriberSource;
use App\Enums\CampaignStatus;
use App\Mail\NewsletterCampaignMail;
use App\Mail\VerifySubscriptionMail;
use App\Events\SubscriberRegistered;
use App\Events\SubscriberVerified;
use App\Events\SubscriberUnsubscribed;
use App\Events\CampaignCreated;
use App\Events\CampaignScheduled;
use App\Events\CampaignStarted;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
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

        // Fire event
        event(new SubscriberRegistered($subscriber));

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

        event(new SubscriberVerified($subscriber));

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

        event(new SubscriberUnsubscribed($subscriber, $reason));

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

        event(new SubscriberVerified($subscriber)); // Fire stats update

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

        event(new CampaignStarted($campaign, $groupId));
    }

    /**
     * Return the cached active subscriber cohorts.
     */
    public function getCachedGroups()
    {
        return Cache::rememberForever('newsletter_active_groups', function () {
            return NewsletterGroup::active()->ordered()->get();
        });
    }

    /**
     * Return the cached email templates.
     */
    public function getCachedTemplates()
    {
        return Cache::rememberForever('newsletter_templates', function () {
            return NewsletterCampaignTemplate::orderBy('name', 'asc')->get();
        });
    }
}
