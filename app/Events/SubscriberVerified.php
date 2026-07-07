<?php

namespace App\Events;

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriberVerified
{
    use Dispatchable, SerializesModels;

    public NewsletterSubscriber $subscriber;

    /**
     * Create a new event instance.
     */
    public function __construct(NewsletterSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }
}
