<?php

namespace App\Enums;

enum CampaignStatus: string
{
    case DRAFT = 'draft';
    case SCHEDULED = 'scheduled';
    case SENDING = 'sending';
    case SENT = 'sent';
    case CANCELLED = 'cancelled';
}
