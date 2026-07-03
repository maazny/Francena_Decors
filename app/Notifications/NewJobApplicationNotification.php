<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobApplicationNotification extends Notification implements ShouldQueue
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
            ->subject("New Job Application: {$this->application->full_name} - {$jobTitle}")
            ->greeting("Hello Admin,")
            ->line("A new application has been submitted for the position of **{$jobTitle}**.")
            ->line("Candidate: **{$this->application->full_name}**")
            ->line("Email: {$this->application->email}")
            ->line("Phone: {$this->application->phone}")
            ->line("Experience: {$this->application->years_of_experience} years")
            ->action('View Application', route('admin.careers.applications.show', $this->application->id))
            ->line('Thank you for using the Careers portal!');
    }
}
