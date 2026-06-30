<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class HeroSlider extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const CACHE_KEY = 'hero_sliders_active';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'desktop_image_id',
        'mobile_image_id',
        'background_video_id',
        'overlay_color',
        'overlay_opacity',
        'text_alignment',
        'content_position',
        'button_one_text',
        'button_one_url',
        'button_one_target',
        'button_two_text',
        'button_two_url',
        'button_two_target',
        'badge_text',
        'badge_color',
        'enable_animation',
        'animation_type',
        'animation_duration',
        'display_order',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'overlay_opacity' => 'integer',
        'enable_animation' => 'boolean',
        'animation_duration' => 'integer',
        'display_order' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
    ];

    public static function getActiveCached()
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return self::query()
                ->with(['desktopImage', 'mobileImage', 'backgroundVideo'])
                ->active()
                ->published()
                ->ordered()
                ->get();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function desktopImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'desktop_image_id');
    }

    public function mobileImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'mobile_image_id');
    }

    public function backgroundVideo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'background_video_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where(function (Builder $builder) use ($now): void {
                $builder->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function (Builder $builder) use ($now): void {
                $builder->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderByDesc('created_at');
    }

    public function getOverlayRgbaAttribute(): string
    {
        $color = $this->overlay_color ?: '#000000';
        $opacity = max(0, min(100, $this->overlay_opacity ?? 65)) / 100;

        if (! preg_match('/^#([a-f0-9]{6})$/i', $color, $matches)) {
            return "rgba(0, 0, 0, {$opacity})";
        }

        $hex = $matches[1];
        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));

        return "rgba({$red}, {$green}, {$blue}, {$opacity})";
    }

    public function isScheduledNow(): bool
    {
        $now = now();

        return (! $this->start_date || $this->start_date->lte($now))
            && (! $this->end_date || $this->end_date->gte($now));
    }
}
