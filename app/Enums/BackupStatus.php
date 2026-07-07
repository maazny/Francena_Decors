<?php

namespace App\Enums;

enum BackupStatus: string
{
    case PENDING = 'pending';
    case RUNNING = 'running';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case RESTORING = 'restoring';
    case RESTORED = 'restored';
}
