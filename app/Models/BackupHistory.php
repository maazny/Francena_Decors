<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Enums\BackupType;
use App\Enums\BackupStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BackupHistory
 * @package App\Models
 *
 * @property int $id
 * @property string $uuid
 * @property string $backup_name
 * @property BackupType $backup_type
 * @property string|null $description
 * @property string $storage_disk
 * @property string $storage_path
 * @property string $file_name
 * @property string|null $file_extension
 * @property string|null $mime_type
 * @property int $file_size
 * @property string|null $checksum
 * @property bool $compression
 * @property bool $encryption
 * @property BackupStatus $status
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property int|null $duration_seconds
 * @property int|null $created_by
 * @property int|null $restored_by
 * @property string|null $restore_point
 * @property bool $is_verified
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $last_downloaded_at
 * @property int $download_count
 * @property string|null $failure_reason
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class BackupHistory extends Model
{
    use HasFactory, HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'backup_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'backup_name',
        'backup_type',
        'description',
        'storage_disk',
        'storage_path',
        'file_name',
        'file_extension',
        'mime_type',
        'file_size',
        'checksum',
        'compression',
        'encryption',
        'status',
        'started_at',
        'completed_at',
        'duration_seconds',
        'created_by',
        'restored_by',
        'restore_point',
        'is_verified',
        'notes',
        'last_downloaded_at',
        'download_count',
        'failure_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'backup_type' => BackupType::class,
        'status' => BackupStatus::class,
        'compression' => 'boolean',
        'encryption' => 'boolean',
        'is_verified' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
        'file_size' => 'integer',
        'download_count' => 'integer',
        'duration_seconds' => 'integer',
    ];

    /**
     * User who created/initiated the backup.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who executed the restore.
     */
    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }
}
