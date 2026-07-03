<?php

namespace App\Events;

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriberRegistered
{
    use Dispatchable, SerializesModels;

    public $subscriber;

    public function __construct(NewsletterSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }
}
