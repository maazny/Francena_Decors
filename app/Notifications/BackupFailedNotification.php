<?php

namespace App\Notifications;

use App\Models\BackupHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BackupFailedNotification extends Notification
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
            ->error()
            ->subject("ALERT: System Backup Failed: {$this->backup->backup_name}")
            ->greeting("Hello, Admin")
            ->line("The system backup process encountered an error and failed.")
            ->line("Backup Name: {$this->backup->backup_name}")
            ->line("Failure Reason: {$this->backup->failure_reason}")
            ->action("View Logs", url("/admin/backups/{$this->backup->id}"))
            ->line("Please investigate database locks or storage parameters immediately.");
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'backup_id' => $this->backup->id,
            'backup_name' => $this->backup->backup_name,
            'status' => 'failed',
            'message' => "Backup '{$this->backup->backup_name}' failed to complete.",
        ];
    }
}
