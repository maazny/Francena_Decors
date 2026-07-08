<?php

namespace App\Enums;

/**
 * Class ReportPeriod
 * @package App\Enums
 */
enum ReportPeriod: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
    case CUSTOM = 'custom';
}
