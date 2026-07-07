<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StorageLowNotification extends Notification
{
    use Queueable;

    protected string $disk;
    protected float $freePercentage;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $disk, float $freePercentage)
    {
        $this->disk = $disk;
        $this->freePercentage = $freePercentage;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->warning()
            ->subject("WARNING: Low Disk Space Alert for {$this->disk}")
            ->greeting("Hello, Admin")
            ->line("The available free storage space on disk '{$this->disk}' is critically low.")
            ->line("Free Space Percentage: " . number_format($this->freePercentage, 2) . "%")
            ->line("Please prune old backup archives or expand drive capacity to prevent database write blocks.");
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'disk' => $this->disk,
            'free_percentage' => $this->freePercentage,
            'message' => "Warning: Storage disk '{$this->disk}' is running low (Free: " . number_format($this->freePercentage, 2) . "%).",
        ];
    }
}
