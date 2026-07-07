<?php

namespace App\Jobs;

use App\Models\NewsletterSubscriber;
use App\Enums\SubscriptionStatus;
use App\Events\SubscriberVerified;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VerifySubscriberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $token;

    /**
     * Create a new job instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $subscriber = NewsletterSubscriber::where('verification_token', $this->token)
            ->where('status', SubscriptionStatus::PENDING)
            ->first();

        if (!$subscriber) {
            Log::warning("VerifySubscriberJob triggered with invalid token: {$this->token}");
            return;
        }

        $subscriber->update([
            'status' => SubscriptionStatus::ACTIVE,
            'verification_status' => true,
            'verification_token' => null,
            'verified_at' => Carbon::now(),
            'subscribed_at' => Carbon::now(),
        ]);

        event(new SubscriberVerified($subscriber));
    }
}
