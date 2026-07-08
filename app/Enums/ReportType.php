<?php

namespace App\Enums;

/**
 * Class ReportType
 * @package App\Enums
 */
enum ReportType: string
{
    case DASHBOARD = 'dashboard';
    case ACTIVITY = 'activity';
    case USERS = 'users';
    case CONTENT = 'content';
    case SEO = 'seo';
    case MEDIA = 'media';
    case NEWSLETTER = 'newsletter';
    case CONTACTS = 'contacts';
    case BACKUP = 'backup';
    case API = 'api';
    case SYSTEM = 'system';
    case CUSTOM = 'custom';
}
