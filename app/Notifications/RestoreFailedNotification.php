<?php

namespace App\Notifications;

use App\Models\BackupHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RestoreFailedNotification extends Notification
{
    use Queueable;

    protected BackupHistory $backup;
    protected string $error;

    /**
     * Create a new notification instance.
     */
    public function __construct(BackupHistory $backup, string $error)
    {
        $this->backup = $backup;
        $this->error = $error;
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
            ->subject("CRITICAL ALERT: System Restore Failed")
            ->greeting("Hello, Admin")
            ->line("A system restoration operation failed to complete, which may leave data files corrupted.")
            ->line("Restore Point target: {$this->backup->backup_name}")
            ->line("Error Message: {$this->error}")
            ->action("View Backup Detail", url("/admin/backups/{$this->backup->id}"))
            ->line("Please check database states and server logs immediately.");
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'backup_id' => $this->backup->id,
            'backup_name' => $this->backup->backup_name,
            'status' => 'restore_failed',
            'message' => "System restore failed: {$this->error}",
        ];
    }
}
