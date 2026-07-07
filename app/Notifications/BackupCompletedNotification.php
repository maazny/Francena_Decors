<?php

namespace App\Notifications;

use App\Models\BackupHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BackupCompletedNotification extends Notification
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
            ->subject("System Backup Succeeded: {$this->backup->backup_name}")
            ->greeting("Hello, Admin")
            ->line("The system backup process completed successfully.")
            ->line("Backup Name: {$this->backup->backup_name}")
            ->line("Category: " . ucfirst($this->backup->backup_type->value))
            ->line("Disk: {$this->backup->storage_disk}")
            ->line("Size: " . number_format($this->backup->file_size / 1024 / 1024, 2) . " MB")
            ->action("View Backup details", url("/admin/backups/{$this->backup->id}"))
            ->line("Thank you for using Fancy Decorators CMS!");
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'backup_id' => $this->backup->id,
            'backup_name' => $this->backup->backup_name,
            'status' => 'completed',
            'message' => "Backup '{$this->backup->backup_name}' completed successfully.",
        ];
    }
}
