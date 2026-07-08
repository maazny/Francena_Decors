<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\MetricGroup;

class AnalyticsSnapshot extends Model
{
    protected $fillable = [
        'snapshot_date',
        'metric_group',
        'metric_name',
        'metric_value',
        'metadata',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'metric_group' => MetricGroup::class,
        'metric_value' => 'float',
        'metadata' => 'array',
    ];

    /**
     * Scope query to specific metric group.
     */
    public function scopeForGroup($query, MetricGroup $group)
    {
        return $query->where('metric_group', $group);
    }

    /**
     * Scope query to specific date range.
     */
    public function scopeForDateRange($query, $from, $to)
    {
        return $query->whereBetween('snapshot_date', [$from, $to]);
    }
}
