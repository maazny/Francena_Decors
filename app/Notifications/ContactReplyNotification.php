<?php

namespace App\Notifications;

use App\Models\ContactReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reply;

    public function __construct(ContactReply $reply)
    {
        $this->reply = $reply;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $contact = $this->reply->contact;
        $subject = "Re: " . ($contact->subject ?? "Your Inquiry");
        
        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Dear {$contact->name},")
            ->line("Thank you for contacting Francena Decors. Here is a response regarding your inquiry:")
            ->line("---")
            ->line($this->reply->message)
            ->line("---");

        // If an attachment exists, attach it to the email response
        if ($this->reply->attachmentMedia) {
            $path = storage_path('app/public/' . $this->reply->attachmentMedia->file_path);
            if (file_exists($path)) {
                $mail->attach($path, [
                    'as' => $this->reply->attachmentMedia->original_name,
                    'mime' => $this->reply->attachmentMedia->mime_type,
                ]);
            }
        }

        $mail->line("If you have any further questions, feel free to reply directly to this email.")
             ->line("Best regards,")
             ->line("Francena Decors Team");

        return $mail;
    }
}
