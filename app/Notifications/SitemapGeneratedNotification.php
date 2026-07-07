<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SitemapGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $sitemapPath;

    public function __construct(string $sitemapPath)
    {
        $this->sitemapPath = $sitemapPath;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Sitemap Generated Successfully',
            'message' => 'The XML sitemap index has been regenerated at: ' . $this->sitemapPath,
            'type' => 'seo_sitemap',
        ];
    }
}
