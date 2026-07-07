<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Enums\BackupType;
use App\Enums\BackupFrequency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BackupSchedule
 * @package App\Models
 *
 * @property int $id
 * @property string $uuid
 * @property string $schedule_name
 * @property BackupType $backup_type
 * @property BackupFrequency $frequency
 * @property string|null $cron_expression
 * @property string $storage_disk
 * @property int $retain_backups
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $last_run_at
 * @property \Illuminate\Support\Carbon|null $next_run_at
 * @property int|null $created_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class BackupSchedule extends Model
{
    use HasFactory, HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'backup_schedules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'schedule_name',
        'backup_type',
        'frequency',
        'cron_expression',
        'storage_disk',
        'retain_backups',
        'is_active',
        'last_run_at',
        'next_run_at',
        'created_by',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'backup_type' => BackupType::class,
        'frequency' => BackupFrequency::class,
        'retain_backups' => 'integer',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    /**
     * User who created the schedule.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
