<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'layout',
        'logo_media_id',
        'background_media_id',
        'company_description',
        'show_logo',
        'show_description',
        'show_columns',
        'show_contact',
        'show_business_hours',
        'show_social_links',
        'show_widgets',
        'newsletter_enabled',
        'newsletter_title',
        'newsletter_description',
        'newsletter_placeholder',
        'newsletter_button_text',
        'contact_heading',
        'contact_address',
        'contact_phone',
        'contact_email',
        'business_hours_heading',
        'copyright_text',
        'bottom_bar_enabled',
        'bottom_bar_text',
        'background_color',
        'text_color',
        'heading_color',
        'link_color',
        'bottom_background_color',
        'status',
    ];

    protected $casts = [
        'show_logo' => 'boolean',
        'show_description' => 'boolean',
        'show_columns' => 'boolean',
        'show_contact' => 'boolean',
        'show_business_hours' => 'boolean',
        'show_social_links' => 'boolean',
        'show_widgets' => 'boolean',
        'newsletter_enabled' => 'boolean',
        'bottom_bar_enabled' => 'boolean',
        'status' => 'boolean',
    ];

    public static function getCached(): self
    {
        return Cache::rememberForever('footer_settings', function () {
            return self::firstOrCreate([])->load([
                'logo',
                'background',
                'columns.links',
                'socialLinks',
                'businessHours',
                'widgets',
            ]);
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('footer_settings');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_media_id');
    }

    public function background(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'background_media_id');
    }

    public function columns(): HasMany
    {
        return $this->hasMany(FooterColumn::class)->orderBy('sort_order');
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(FooterSocialLink::class)->orderBy('sort_order');
    }

    public function businessHours(): HasMany
    {
        return $this->hasMany(FooterBusinessHour::class)->orderBy('sort_order');
    }

    public function widgets(): HasMany
    {
        return $this->hasMany(FooterWidget::class)->orderBy('sort_order');
    }
}
