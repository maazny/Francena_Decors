<?php

use App\Models\HeroSlider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

if (! function_exists('hero_slides')) {
    /**
     * Get cached active hero slides.
     */
    function hero_slides(): Collection
    {
        if (! Schema::hasTable('hero_sliders')) {
            return collect();
        }

        return HeroSlider::getActiveCached();
    }
}
