<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'full_name',
        'slug',
        'designation',
        'short_bio',
        'full_bio',
        'profile_photo_id',
        'cover_photo_id',
        'email',
        'phone',
        'experience_years',
        'qualification',
        'specialization',
        'display_order',
        'featured',
        'homepage_featured',
        'joining_date',
        'status',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'display_order' => 'integer',
        'featured' => 'boolean',
        'homepage_featured' => 'boolean',
        'joining_date' => 'date',
        'status' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(TeamDepartment::class, 'department_id');
    }

    public function profilePhoto()
    {
        return $this->belongsTo(Media::class, 'profile_photo_id');
    }

    public function coverPhoto()
    {
        return $this->belongsTo(Media::class, 'cover_photo_id');
    }

    public function socialLinks()
    {
        return $this->hasMany(TeamSocialLink::class, 'team_member_id')->ordered();
    }

    public function skills()
    {
        return $this->hasMany(TeamSkill::class, 'team_member_id')->ordered();
    }

    public function certifications()
    {
        return $this->hasMany(TeamCertification::class, 'team_member_id')->ordered();
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeHomepageFeatured($query)
    {
        return $query->where('homepage_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderByDesc('created_at');
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        $term = "%{$term}%";

        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', $term)
                ->orWhere('designation', 'like', $term)
                ->orWhere('specialization', 'like', $term);
        });
    }
}
