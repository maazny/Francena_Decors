<?php

namespace App\Enums;

/**
 * Class ReportStatus
 * @package App\Enums
 */
enum ReportStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case ARCHIVED = 'archived';
}
