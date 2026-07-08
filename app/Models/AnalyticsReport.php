<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;
use App\Enums\ReportType;
use App\Enums\ReportStatus;
use App\Enums\ReportPeriod;

/**
 * Class AnalyticsReport
 *
 * @property int $id
 * @property string $uuid
 * @property string $report_name
 * @property ReportType $report_type
 * @property string $module
 * @property ReportPeriod $period
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property int|null $generated_by
 * @property ReportStatus $status
 * @property int $total_records
 * @property array|null $report_data
 * @property array|null $filters
 * @property string|null $file_path
 * @property string|null $file_type
 * @property int $file_size
 * @property \Carbon\Carbon|null $generated_at
 * @property int $download_count
 * @property \Carbon\Carbon|null $last_downloaded_at
 * @property string|null $notes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property User|null $generator
 */
class AnalyticsReport extends Model
{
    use HasFactory, HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'analytics_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'report_name',
        'report_type',
        'module',
        'period',
        'start_date',
        'end_date',
        'generated_by',
        'status',
        'total_records',
        'report_data',
        'filters',
        'file_path',
        'file_type',
        'file_size',
        'generated_at',
        'download_count',
        'last_downloaded_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'report_type' => ReportType::class,
        'period' => ReportPeriod::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => ReportStatus::class,
        'report_data' => 'array',
        'filters' => 'array',
        'generated_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
    ];

    /**
     * Get the user who generated this report.
     *
     * @return BelongsTo
     */
    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
