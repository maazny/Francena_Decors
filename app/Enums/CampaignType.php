<?php

namespace App\Enums;

enum CampaignType: string
{
    case NEWSLETTER = 'newsletter';
    case PROMOTION = 'promotion';
    case ANNOUNCEMENT = 'announcement';
    case CUSTOM = 'custom';
}
