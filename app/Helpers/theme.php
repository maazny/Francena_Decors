<?php

use App\Models\ThemeSetting;

if (! function_exists('theme_setting')) {
    /**
     * Get the current cached theme settings.
     */
    function theme_setting(): ThemeSetting
    {
        return ThemeSetting::getCached();
    }
}

if (! function_exists('theme_css_variables')) {
    /**
     * Get CSS variables for the current theme.
     */
    function theme_css_variables(): string
    {
        return theme_setting()->toCssVariables();
    }
}
