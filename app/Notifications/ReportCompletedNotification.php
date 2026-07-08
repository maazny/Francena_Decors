<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\AnalyticsReport;

/**
 * Class ReportCompletedNotification
 * @package App\Notifications
 */
class ReportCompletedNotification extends Notification
{
    use Queueable;

    /**
     * @var AnalyticsReport
     */
    protected $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(AnalyticsReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Analytics Report Completed')
            ->line("The report '{$this->report->report_name}' has been successfully compiled.")
            ->action('View Report', route('admin.reports.show', $this->report->id))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'report_name' => $this->report->report_name,
            'message' => "The analytical report '{$this->report->report_name}' has completed successfully.",
        ];
    }
}
