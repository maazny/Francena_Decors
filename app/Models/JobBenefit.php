<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_opening_id',
        'benefit_name',
        'display_order',
    ];

    protected $casts = [
        'job_opening_id' => 'integer',
        'display_order' => 'integer',
    ];

    /**
     * Get the job opening that owns this benefit.
     */
    public function jobOpening(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class, 'job_opening_id');
    }

    /**
     * Scope a query to order benefits by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('benefit_name');
    }
}
