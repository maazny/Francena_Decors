<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class GenericHtmlMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $htmlContent;
    public ?string $plainContent;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $subject,
        string $htmlContent,
        string $senderName,
        string $senderEmail,
        ?string $plainContent = null
    ) {
        $this->subject = $subject;
        $this->htmlContent = $htmlContent;
        $this->plainContent = $plainContent;
        $this->from = [new Address($senderEmail, $senderName)];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->from[0],
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->htmlContent,
            textString: $this->plainContent ?: strip_tags($this->htmlContent),
        );
    }
}
