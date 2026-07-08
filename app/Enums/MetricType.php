<?php

namespace App\Enums;

/**
 * Class MetricType
 * @package App\Enums
 */
enum MetricType: string
{
    case COUNTER = 'counter';
    case PERCENTAGE = 'percentage';
    case SIZE = 'size';
    case DURATION = 'duration';
    case PERFORMANCE = 'performance';
    case TRAFFIC = 'traffic';
    case GROWTH = 'growth';
}
