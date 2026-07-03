<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdatedNotification extends Notification implements ShouldQueue
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
        $status = $this->application->application_status;
        $jobTitle = $this->application->jobOpening?->title ?? 'a Position';
        
        $mail = (new MailMessage)
            ->subject("Update regarding your application at Fancy Decorators: {$jobTitle}")
            ->greeting("Dear {$this->application->full_name},");

        if ($status === 'interviewing') {
            $mail->line("We are pleased to invite you for an interview for the **{$jobTitle}** position at Fancy Decorators.")
                 ->line("Our recruitment team will contact you shortly to schedule a convenient time and date.")
                 ->line("If you have any initial portfolio pieces or project documentation, please have them ready.");
        } elseif ($status === 'shortlisted') {
            $mail->line("Great news! Your profile for the **{$jobTitle}** role has been shortlisted.")
                 ->line("Our hiring team is currently scheduling the next stage of evaluations and will follow up with you soon.");
        } elseif ($status === 'rejected') {
            $mail->line("Thank you for your interest in the **{$jobTitle}** position at Fancy Decorators.")
                 ->line("After a thorough review of all applicants, we regret to inform you that we will not be moving forward with your application at this time.")
                 ->line("We appreciate your time, effort, and interest in our company, and we wish you success in your professional career.");
        } elseif ($status === 'offered') {
            $mail->line("Congratulations! We are delighted to offer you the position of **{$jobTitle}**.")
                 ->line("Our HR team will reach out to you shortly with details regarding the formal offer letter, onboarding timeline, and next steps.");
        } else {
            $mail->line("This is to inform you that your job application status for the position **{$jobTitle}** has been updated to: **" . ucfirst($status) . "**.");
        }

        $mail->line("Thank you for your interest in Fancy Decorators.")
             ->line("Best regards,")
             ->line("Fancy Decorators Recruitment Team");

        return $mail;
    }
}
