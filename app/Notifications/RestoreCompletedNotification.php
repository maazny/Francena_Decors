<?php

namespace App\Notifications;

use App\Models\BackupHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RestoreCompletedNotification extends Notification
{
    use Queueable;

    protected BackupHistory $backup;

    /**
     * Create a new notification instance.
     */
    public function __construct(BackupHistory $backup)
    {
        $this->backup = $backup;
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
            ->subject("System Restore Completed Successfully")
            ->greeting("Hello, Admin")
            ->line("A system restoration operation has completed successfully.")
            ->line("Restored From Point: {$this->backup->backup_name}")
            ->line("Mapped Date: " . $this->backup->created_at->toDayDateTimeString())
            ->action("Visit Admin Dashboard", url("/admin/dashboard"))
            ->line("All systems have been verified green.");
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'backup_id' => $this->backup->id,
            'backup_name' => $this->backup->backup_name,
            'status' => 'restored',
            'message' => "System successfully restored from '{$this->backup->backup_name}' point.",
        ];
    }
}
