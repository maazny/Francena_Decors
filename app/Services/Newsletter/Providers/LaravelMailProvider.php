<?php

namespace App\Services\Newsletter\Providers;

use App\Services\Newsletter\EmailProviderInterface;
use App\Mail\GenericHtmlMail;
use Illuminate\Support\Facades\Mail;

class LaravelMailProvider implements EmailProviderInterface
{
    /**
     * Send a single transactional or campaign email using Laravel Mail.
     */
    public function send(
        string $to,
        string $subject,
        string $htmlContent,
        string $senderName,
        string $senderEmail,
        ?string $plainContent = null
    ): bool {
        Mail::to($to)->send(
            new GenericHtmlMail($subject, $htmlContent, $senderName, $senderEmail, $plainContent)
        );

        return true;
    }
}
