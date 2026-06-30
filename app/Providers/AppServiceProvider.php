<?php

namespace App\Providers;

use App\Models\HeaderLogo;
use App\Models\HeaderSetting;
use App\Models\HeaderTopbar;
use App\Models\ThemeSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/theme.php');
        require_once app_path('Helpers/media.php');
        require_once app_path('Helpers/header.php');
        require_once app_path('Helpers/footer.php');
        require_once app_path('Helpers/hero.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('themeSettings', ThemeSetting::getCached());

            if (Schema::hasTable('header_settings')) {
                $view->with('headerSettings', HeaderSetting::getCached());
            } else {
                $view->with('headerSettings', new HeaderSetting);
            }

            if (Schema::hasTable('header_topbars')) {
                $view->with('headerTopbar', HeaderTopbar::firstOrCreate([]));
            } else {
                $view->with('headerTopbar', new HeaderTopbar);
            }

            if (Schema::hasTable('header_logos')) {
                $view->with('headerLogo', HeaderLogo::firstOrCreate([]));
            } else {
                $view->with('headerLogo', new HeaderLogo);
            }
        });
    }
}
