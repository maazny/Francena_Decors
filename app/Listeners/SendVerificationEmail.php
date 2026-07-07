<?php

namespace App\Listeners;

use App\Events\SubscriberRegistered;
use App\Mail\VerifySubscriptionMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendVerificationEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(SubscriberRegistered $event): void
    {
        $subscriber = $event->subscriber;

        if (config('newsletter.double_opt_in', true)) {
            try {
                Mail::to($subscriber->email)->send(new VerifySubscriptionMail($subscriber));
            } catch (\Exception $e) {
                Log::error("Failed to send verification email for subscriber ID {$subscriber->id}: " . $e->getMessage());
            }
        }
    }
}
