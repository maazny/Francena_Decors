<?php

namespace App\Enums;

enum BackupType: string
{
    case DATABASE = 'database';
    case STORAGE = 'storage';
    case MEDIA = 'media';
    case FULL = 'full';
    case CUSTOM = 'custom';
}
