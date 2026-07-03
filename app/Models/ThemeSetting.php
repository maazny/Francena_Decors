<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ThemeSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'surface_color',
        'text_color',
        'heading_color',
        'link_color',
        'link_hover_color',
        'button_background',
        'button_text_color',
        'button_hover_background',
        'button_hover_text',
        'navbar_background',
        'navbar_text_color',
        'footer_background',
        'footer_text_color',
        'sidebar_background',
        'sidebar_text_color',
        'card_background',
        'card_border_color',
        'input_background',
        'input_border_color',
        'success_color',
        'warning_color',
        'danger_color',
        'info_color',
        'font_family',
        'heading_font',
        'base_font_size',
        'border_radius',
        'box_shadow',
        'container_width',
        'loader_enabled',
        'loader_style',
        'loader_color',
        'loader_background',
        'dark_mode',
        'animation_enabled',
        'animation_speed',
        'custom_css',
        'custom_js',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_font_size' => 'integer',
        'container_width' => 'integer',
        'loader_enabled' => 'boolean',
        'dark_mode' => 'boolean',
        'animation_enabled' => 'boolean',
        'animation_speed' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * Return the cached theme settings instance.
     */
    public static function getCached(): self
    {
        return Cache::rememberForever('theme_settings', function () {
            return self::firstOrCreate([]);
        });
    }

    /**
     * Clear the theme settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('theme_settings');
        Cache::forget('contact.site_settings');
    }

    /**
     * Returns a safe custom CSS string, stripped of script tags.
     */
    public function safeCustomCss(): string
    {
        return strip_tags($this->custom_css, '<style><br><span><div><p><strong><em><u><a><ul><ol><li><blockquote><hr><code><pre>');
    }

    /**
     * Returns a safe custom JavaScript string, stripped of script tags.
     */
    public function safeCustomJs(): string
    {
        return strip_tags($this->custom_js);
    }

    /**
     * Returns CSS variables generated from theme settings.
     */
    public function toCssVariables(): string
    {
        return collect([
            '--primary-color' => $this->sanitizeCssValue($this->primary_color),
            '--secondary-color' => $this->sanitizeCssValue($this->secondary_color),
            '--accent-color' => $this->sanitizeCssValue($this->accent_color),
            '--background-color' => $this->sanitizeCssValue($this->background_color),
            '--surface-color' => $this->sanitizeCssValue($this->surface_color),
            '--text-color' => $this->sanitizeCssValue($this->text_color),
            '--heading-color' => $this->sanitizeCssValue($this->heading_color),
            '--link-color' => $this->sanitizeCssValue($this->link_color),
            '--link-hover-color' => $this->sanitizeCssValue($this->link_hover_color),
            '--button-background' => $this->sanitizeCssValue($this->button_background),
            '--button-text-color' => $this->sanitizeCssValue($this->button_text_color),
            '--button-hover-background' => $this->sanitizeCssValue($this->button_hover_background),
            '--button-hover-text' => $this->sanitizeCssValue($this->button_hover_text),
            '--navbar-background' => $this->sanitizeCssValue($this->navbar_background),
            '--navbar-text-color' => $this->sanitizeCssValue($this->navbar_text_color),
            '--footer-background' => $this->sanitizeCssValue($this->footer_background),
            '--footer-text-color' => $this->sanitizeCssValue($this->footer_text_color),
            '--sidebar-background' => $this->sanitizeCssValue($this->sidebar_background),
            '--sidebar-text-color' => $this->sanitizeCssValue($this->sidebar_text_color),
            '--card-background' => $this->sanitizeCssValue($this->card_background),
            '--card-border-color' => $this->sanitizeCssValue($this->card_border_color),
            '--input-background' => $this->sanitizeCssValue($this->input_background),
            '--input-border-color' => $this->sanitizeCssValue($this->input_border_color),
            '--success-color' => $this->sanitizeCssValue($this->success_color),
            '--warning-color' => $this->sanitizeCssValue($this->warning_color),
            '--danger-color' => $this->sanitizeCssValue($this->danger_color),
            '--info-color' => $this->sanitizeCssValue($this->info_color),
            '--font-family' => $this->sanitizeCssValue($this->font_family),
            '--heading-font' => $this->sanitizeCssValue($this->heading_font),
            '--base-font-size' => $this->base_font_size ? $this->sanitizeCssValue($this->base_font_size . 'px') : null,
            '--border-radius' => $this->sanitizeCssValue($this->border_radius),
            '--box-shadow' => $this->sanitizeCssValue($this->box_shadow),
            '--container-width' => $this->container_width ? $this->sanitizeCssValue($this->container_width . 'px') : null,
        ])
            ->filter()
            ->map(fn ($value, $key) => "{$key}: {$value};")
            ->implode('\n');
    }

    /**
     * Sanitize CSS variable values.
     */
    protected function sanitizeCssValue(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        return trim(preg_replace('/[^\w\s\#\-\.,%\(\)\'\"\:]/', '', $value));
    }
}
