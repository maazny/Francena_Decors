<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'name',
        'slug',
        'description',
        'display_order',
        'status',
    ];

    protected $casts = [
        'department_id' => 'integer',
        'display_order' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * Get the department that owns the category.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(JobDepartment::class, 'department_id');
    }

    /**
     * Get all job openings belonging to the category.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(JobOpening::class, 'category_id');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to order categories by display order and name.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }
}
