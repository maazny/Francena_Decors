<?php

use App\Models\FooterSetting;
use Illuminate\Support\Facades\Schema;

if (! function_exists('footer_setting')) {
    /**
     * Get the current cached footer settings.
     */
    function footer_setting(): FooterSetting
    {
        if (! Schema::hasTable('footer_settings')) {
            return new FooterSetting;
        }

        return FooterSetting::getCached();
    }
}
