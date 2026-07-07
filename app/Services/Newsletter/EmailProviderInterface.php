<?php

namespace App\Services\Newsletter;

interface EmailProviderInterface
{
    /**
     * Send a single transactional or campaign email.
     *
     * @param string $to Recipient email address.
     * @param string $subject Email subject line.
     * @param string $htmlContent HTML body content.
     * @param string $senderName Sender name.
     * @param string $senderEmail Sender email.
     * @param string|null $plainContent Optional plain text body backup.
     * @return bool
     */
    public function send(
        string $to,
        string $subject,
        string $htmlContent,
        string $senderName,
        string $senderEmail,
        ?string $plainContent = null
    ): bool;
}
