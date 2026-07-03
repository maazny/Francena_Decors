<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_opening_id',
        'full_name',
        'email',
        'phone',
        'resume_media_id',
        'cover_letter',
        'years_of_experience',
        'current_company',
        'expected_salary',
        'application_status',
        'admin_notes',
        'applied_at',
    ];

    protected $casts = [
        'job_opening_id' => 'integer',
        'resume_media_id' => 'integer',
        'years_of_experience' => 'decimal:1',
        'expected_salary' => 'decimal:2',
        'applied_at' => 'datetime',
    ];

    /**
     * Get the job opening this application is for.
     */
    public function jobOpening(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class, 'job_opening_id');
    }

    /**
     * Get the resume file associated with the application.
     */
    public function resumeMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'resume_media_id');
    }
}
