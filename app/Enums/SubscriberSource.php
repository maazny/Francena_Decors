<?php

namespace App\Enums;

enum SubscriberSource: string
{
    case WEBSITE = 'website';
    case ADMIN = 'admin';
    case IMPORT = 'import';
    case API = 'api';
}
