<?php

namespace App\Jobs;

use App\Models\NewsletterSubscriber;
use App\Mail\VerifySubscriptionMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendVerificationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public NewsletterSubscriber $subscriber;

    /**
     * Create a new job instance.
     */
    public function __construct(NewsletterSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->subscriber->email)->send(new VerifySubscriptionMail($this->subscriber));
        } catch (\Exception $e) {
            Log::error("Async SendVerificationEmailJob failed for subscriber ID {$this->subscriber->id}: " . $e->getMessage());
        }
    }
}
