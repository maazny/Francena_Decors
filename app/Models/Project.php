<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'project_category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'client_name',
        'client_company',
        'client_logo_id',
        'budget',
        'currency',
        'project_manager',
        'start_date',
        'end_date',
        'completion_percentage',
        'status',
        'featured',
        'homepage_featured',
        'location',
        'google_map_embed',
        'latitude',
        'longitude',
        'cover_image_id',
        'banner_image_id',
        'video_url',
        'youtube_url',
        'project_duration',
        'project_area',
        'display_order',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'completion_percentage' => 'integer',
        'featured' => 'boolean',
        'homepage_featured' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'display_order' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_image_id');
    }

    public function bannerImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'banner_image_id');
    }

    public function clientLogo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'client_logo_id');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ProjectGallery::class)->ordered();
    }

    public function beforeAfters(): HasMany
    {
        return $this->hasMany(ProjectBeforeAfter::class)->ordered();
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(ProjectTimeline::class)->ordered();
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ProjectMaterial::class)->ordered();
    }

    public function technologies(): HasMany
    {
        return $this->hasMany(ProjectTechnology::class)->ordered();
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(ProjectTeamMember::class)->ordered();
    }

    public function relatedProjects(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'related_projects', 'project_id', 'related_project_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeHomepageFeatured(Builder $query): Builder
    {
        return $query->where('homepage_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderBy('title');
    }

    public function getIsCompletedAttribute(): bool
    {
        return (int) $this->completion_percentage >= 100;
    }
}
