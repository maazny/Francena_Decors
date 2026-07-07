<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class ActivityLog
 * @package App\Models
 *
 * @property int $id
 * @property string $uuid
 * @property int|null $user_id
 * @property int|null $role_id
 * @property string $module
 * @property ActivityAction $action
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string|null $description
 * @property array|null $old_values
 * @property array|null $new_values
 * @property string|null $ip_address
 * @property string|null $browser
 * @property string|null $device
 * @property string|null $operating_system
 * @property string|null $user_agent
 * @property string|null $url
 * @property string|null $method
 * @property string|null $session_id
 * @property string|null $request_id
 * @property ActivityStatus $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ActivityLog extends Model
{
    use HasFactory, HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'role_id',
        'module',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'browser',
        'device',
        'operating_system',
        'user_agent',
        'url',
        'method',
        'session_id',
        'request_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'action' => ActivityAction::class,
        'status' => ActivityStatus::class,
    ];

    /**
     * Get the user who triggered the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role associated with the activity log at the time.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the parent audited model.
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo('auditable', 'model_type', 'model_id');
    }
}
