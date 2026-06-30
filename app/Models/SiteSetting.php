<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_name',
        'company_name',
        'tagline',
        'company_email',
        'support_email',
        'phone',
        'mobile',
        'whatsapp',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'google_map',
        'office_hours',
        'copyright',
        'footer_text',
        'logo',
        'footer_logo',
        'favicon',
        'default_language',
        'timezone',
        'maintenance_mode',
        'maintenance_message',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'maintenance_mode' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Delete an uploaded image file if it exists.
     */
    public function deleteImage(string $attribute): void
    {
        $path = $this->getAttribute($attribute);

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Get the public URL for the logo.
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? Storage::disk('public')->url($this->logo) : null;
    }

    /**
     * Get the public URL for the footer logo.
     */
    public function getFooterLogoUrlAttribute(): ?string
    {
        return $this->footer_logo ? Storage::disk('public')->url($this->footer_logo) : null;
    }

    /**
     * Get the public URL for the favicon.
     */
    public function getFaviconUrlAttribute(): ?string
    {
        return $this->favicon ? Storage::disk('public')->url($this->favicon) : null;
    }
}
