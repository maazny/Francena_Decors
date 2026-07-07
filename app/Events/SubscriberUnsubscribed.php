<?php

namespace App\Events;

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriberUnsubscribed
{
    use Dispatchable, SerializesModels;

    public NewsletterSubscriber $subscriber;
    public ?string $reason;

    /**
     * Create a new event instance.
     */
    public function __construct(NewsletterSubscriber $subscriber, ?string $reason = null)
    {
        $this->subscriber = $subscriber;
        $this->reason = $reason;
    }
}
