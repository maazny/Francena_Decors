<?php

use App\Models\AboutSection;
use Illuminate\Support\Facades\Schema;

if (! function_exists('about_cms')) {
    /**
     * Get cached About CMS data.
     *
     * @return array<string, mixed>
     */
    function about_cms(): array
    {
        if (! Schema::hasTable('about_sections')) {
            return [
                'section' => new AboutSection,
                'values' => collect(),
                'timelines' => collect(),
                'whyChooseUs' => collect(),
            ];
        }

        return AboutSection::getCached();
    }
}
