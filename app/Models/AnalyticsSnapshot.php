<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;
use App\Enums\MetricType;

/**
 * Class AnalyticsSnapshot
 *
 * @property int $id
 * @property string $uuid
 * @property string $snapshot_name
 * @property MetricType $metric_type
 * @property string $module
 * @property string $metric_key
 * @property float $metric_value
 * @property array|null $metric_data
 * @property \Carbon\Carbon $captured_at
 * @property int|null $created_by
 * @property string|null $notes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property User|null $creator
 */
class AnalyticsSnapshot extends Model
{
    use HasFactory, HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'analytics_snapshots';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'snapshot_name',
        'metric_type',
        'module',
        'metric_key',
        'metric_value',
        'metric_data',
        'captured_at',
        'created_by',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metric_type' => MetricType::class,
        'metric_value' => 'decimal:4',
        'metric_data' => 'array',
        'captured_at' => 'datetime',
    ];

    /**
     * Get the user who captured this snapshot.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
