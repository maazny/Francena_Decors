<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RedirectConflictNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $sourceUrl;
    protected string $conflictTarget;

    public function __construct(string $sourceUrl, string $conflictTarget)
    {
        $this->sourceUrl = $sourceUrl;
        $this->conflictTarget = $conflictTarget;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Redirect Conflict Warning',
            'message' => "A request redirection configuration conflict was flagged for: {$this->sourceUrl} pointing to {$this->conflictTarget}.",
            'type' => 'seo_redirect_conflict',
        ];
    }
}
