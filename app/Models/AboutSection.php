<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class AboutSection extends Model
{
    use HasFactory;

    public const CACHE_KEY = 'about_cms_data';

    protected $fillable = [
        'company_story',
        'mission',
        'vision',
        'chairman_message',
        'chairman_name',
        'chairman_designation',
        'chairman_image_id',
        'company_video_id',
        'brochure_file_id',
        'experience_years',
        'completed_projects',
        'happy_clients',
        'team_members',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image_id',
        'status',
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'completed_projects' => 'integer',
        'happy_clients' => 'integer',
        'team_members' => 'integer',
        'status' => 'boolean',
    ];

    public static function getCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return [
                'section' => self::firstOrCreate([])->load(['chairmanImage', 'companyVideo', 'brochureFile', 'ogImage']),
                'values' => CompanyValue::active()->ordered()->get(),
                'timelines' => CompanyTimeline::with('image')->active()->ordered()->get(),
                'whyChooseUs' => WhyChooseUs::active()->ordered()->get(),
            ];
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function chairmanImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'chairman_image_id');
    }

    public function companyVideo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'company_video_id');
    }

    public function brochureFile(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'brochure_file_id');
    }

    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'og_image_id');
    }
}
