<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOpening extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'category_id',
        'location_id',
        'title',
        'slug',
        'reference_no',
        'short_description',
        'description',
        'employment_type',
        'experience_level',
        'vacancies',
        'salary_from',
        'salary_to',
        'salary_type',
        'application_deadline',
        'featured',
        'homepage_featured',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'department_id' => 'integer',
        'category_id' => 'integer',
        'location_id' => 'integer',
        'vacancies' => 'integer',
        'salary_from' => 'decimal:2',
        'salary_to' => 'decimal:2',
        'application_deadline' => 'datetime',
        'featured' => 'boolean',
        'homepage_featured' => 'boolean',
        'status' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the department that owns the job opening.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(JobDepartment::class, 'department_id');
    }

    /**
     * Get the category that owns the job opening.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    /**
     * Get the location that owns the job opening.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(JobLocation::class, 'location_id');
    }

    /**
     * Get the skills belonging to the job opening.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(JobSkill::class, 'job_opening_id');
    }

    /**
     * Get the benefits belonging to the job opening.
     */
    public function benefits(): HasMany
    {
        return $this->hasMany(JobBenefit::class, 'job_opening_id');
    }

    /**
     * Get the qualifications belonging to the job opening.
     */
    public function qualifications(): HasMany
    {
        return $this->hasMany(JobQualification::class, 'job_opening_id');
    }

    /**
     * Get the applications submitted for the job opening.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_opening_id');
    }

    /**
     * Scope a query to only include active openings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include featured openings.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to only include homepage featured openings.
     */
    public function scopeHomepageFeatured($query)
    {
        return $query->where('homepage_featured', true);
    }

    /**
     * Scope a query to only include published openings.
     */
    public function scopePublished($query)
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope a query to order openings.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc');
    }
}
