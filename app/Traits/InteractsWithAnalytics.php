<?php

declare(strict_types=1);

namespace App\Traits;

use Carbon\Carbon;
use InvalidArgumentException;

/**
 * Trait InteractsWithAnalytics
 * @package App\Traits
 */
trait InteractsWithAnalytics
{
    /**
     * Parse start and end date parameters safely.
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array{0: Carbon, 1: Carbon}
     * @throws InvalidArgumentException
     */
    protected function parseDateRange(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->subMonth()->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        if ($start->greaterThan($end)) {
            throw new InvalidArgumentException('Start date cannot be after end date.');
        }

        return [$start, $end];
    }

    /**
     * Safely calculate percentage variance between two indicators.
     *
     * @param float $previous
     * @param float $current
     * @return float
     */
    protected function calculatePercentageChange(float $previous, float $current): float
    {
        if ($previous == 0.0) {
            return $current > 0.0 ? 100.00 : 0.00;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
}
