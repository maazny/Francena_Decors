<?php

namespace App\Enums;

enum MetricGroup: string
{
    case WEBSITE = 'website';
    case CONTENT = 'content';
    case USER = 'user';
    case ACTIVITY = 'activity';
    case MEDIA = 'media';
    case SEO = 'seo';
    case CONTACT = 'contact';
    case NEWSLETTER = 'newsletter';
    case BACKUP = 'backup';
    case API = 'api';
    case PERFORMANCE = 'performance';
}
