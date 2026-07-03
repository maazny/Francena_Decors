<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $jobTitle = $this->application->jobOpening?->title ?? 'a Position';
        return (new MailMessage)
            ->subject("Application Received: {$jobTitle} - Fancy Decorators")
            ->greeting("Dear {$this->application->full_name},")
            ->line("Thank you for submitting your application for the position of **{$jobTitle}** at Fancy Decorators.")
            ->line("We have received your resume and cover letter. Our recruiting team will review your application shortly and get in touch with you if your qualifications match our current needs.")
            ->line("Position Details:")
            ->line("- **Role**: {$jobTitle}")
            ->line("- **Location**: " . ($this->application->jobOpening?->location?->city ?? 'Main Office'))
            ->line("We appreciate your interest in joining Fancy Decorators.")
            ->line("Best regards,")
            ->line("Fancy Decorators Recruitment Team");
    }
}
