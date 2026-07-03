<?php

namespace App\Enums;

enum ContactSource: string
{
    case WEBSITE = 'website';
    case ADMIN = 'admin';
    case API = 'api';
}
