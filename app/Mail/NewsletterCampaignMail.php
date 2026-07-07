<?php

namespace App\Mail;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class NewsletterCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public NewsletterCampaign $campaign;
    public NewsletterSubscriber $subscriber;

    /**
     * Create a new message instance.
     */
    public function __construct(NewsletterCampaign $campaign, NewsletterSubscriber $subscriber)
    {
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->campaign->sender_email, $this->campaign->sender_name),
            subject: $this->campaign->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Parse personalization tags in the content
        $htmlContent = $this->campaign->html_content;
        $plainText = $this->campaign->plain_text;

        $unsubscribeUrl = route('newsletter.unsubscribe', ['token' => $this->subscriber->unsubscribe_token]);

        $tags = [
            '{{subscriber_name}}' => $this->subscriber->name ?: 'Subscriber',
            '{{name}}' => $this->subscriber->name ?: 'Subscriber',
            '[name]' => $this->subscriber->name ?: 'Subscriber',
            '{{subscriber_email}}' => $this->subscriber->email,
            '{{email}}' => $this->subscriber->email,
            '[email]' => $this->subscriber->email,
            '{{unsubscribe_url}}' => $unsubscribeUrl,
            '[unsubscribe_url]' => $unsubscribeUrl,
        ];

        $htmlContent = str_replace(array_keys($tags), array_values($tags), $htmlContent);
        if ($plainText) {
            $plainText = str_replace(array_keys($tags), array_values($tags), $plainText);
        }

        // Add tracking/unsubscribe footer if not present
        if (strpos($htmlContent, 'unsubscribe') === false && strpos($htmlContent, $unsubscribeUrl) === false) {
            $htmlContent .= '<hr><p style="font-size:11px;color:#999;text-align:center;">You received this email because you subscribed to our newsletter. <a href="' . $unsubscribeUrl . '">Unsubscribe here</a>.</p>';
        }

        return new Content(
            htmlString: $htmlContent,
            textString: $plainText ?: strip_tags($htmlContent),
        );
    }
}
