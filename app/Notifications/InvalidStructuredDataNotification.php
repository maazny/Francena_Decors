<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class InvalidStructuredDataNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $pageTitle;
    protected string $errorDetail;

    public function __construct(string $pageTitle, string $errorDetail)
    {
        $this->pageTitle = $pageTitle;
        $this->errorDetail = $errorDetail;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Invalid Schema JSON-LD Configuration',
            'message' => "JSON validation failed on page '{$this->pageTitle}': {$this->errorDetail}.",
            'type' => 'seo_invalid_schema',
        ];
    }
}
