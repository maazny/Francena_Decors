<?php

namespace App\Enums;

enum ContactStatus: string
{
    case NEW = 'new';
    case OPEN = 'open';
    case CONTACTED = 'contacted';
    case FOLLOW_UP = 'follow_up';
    case CONVERTED = 'converted';
    case CLOSED = 'closed';
    case SPAM = 'spam';
}
