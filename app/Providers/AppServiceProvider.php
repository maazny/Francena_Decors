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
        require_once app_path('Helpers/about.php');
        require_once app_path('Helpers/services.php');

        $this->app->bind(
            \App\Services\Newsletter\EmailProviderInterface::class,
            \App\Services\Newsletter\Providers\LaravelMailProvider::class
        );

        $this->app->bind(
            \App\Contracts\ActivityLogServiceInterface::class,
            \App\Services\ActivityLogService::class
        );

        $this->app->bind(
            \App\Contracts\BackupServiceInterface::class,
            \App\Services\BackupService::class
        );

        $this->app->bind(
            \App\Contracts\BackupScheduleServiceInterface::class,
            \App\Services\BackupScheduleService::class
        );

        $this->app->bind(
            \App\Contracts\AnalyticsServiceInterface::class,
            \App\Services\AnalyticsService::class
        );

        $this->app->bind(
            \App\Contracts\ReportServiceInterface::class,
            \App\Services\ReportService::class
        );

        $this->app->bind(
            \App\Contracts\SnapshotServiceInterface::class,
            \App\Services\SnapshotService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $view->with('themeSettings', ThemeSetting::getCached());
            } catch (\Throwable $e) {
                $view->with('themeSettings', new ThemeSetting);
            }

            try {
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
            } catch (\Throwable $e) {
                $view->with('headerSettings', new HeaderSetting);
                $view->with('headerTopbar', new HeaderTopbar);
                $view->with('headerLogo', new HeaderLogo);
            }
        });

        View::composer('layouts.app', \App\View\Composers\SeoViewComposer::class);

        // Bootstrap Dynamic RBAC Gates
        (new \App\Services\AuthorizationService())->bootstrapGates();

        // Register RBAC Event Listeners
        \Illuminate\Support\Facades\Event::listen([
            \App\Events\RoleCreated::class,
            \App\Events\RoleUpdated::class,
            \App\Events\RoleDeleted::class,
            \App\Events\PermissionCreated::class,
            \App\Events\PermissionUpdated::class,
            \App\Events\PermissionAssigned::class,
            \App\Events\UserRoleAssigned::class,
            \App\Events\UserRoleRemoved::class,
        ], \App\Listeners\ClearRbacCache::class);

        \Illuminate\Support\Facades\Event::listen([
            \App\Events\RoleCreated::class,
            \App\Events\RoleUpdated::class,
            \App\Events\RoleDeleted::class,
            \App\Events\PermissionCreated::class,
            \App\Events\PermissionUpdated::class,
            \App\Events\PermissionAssigned::class,
            \App\Events\UserRoleAssigned::class,
            \App\Events\UserRoleRemoved::class,
        ], \App\Listeners\RecordRbacActivity::class);

        \Illuminate\Support\Facades\Event::listen([
            \App\Events\RoleDeleted::class,
            \App\Events\UserRoleAssigned::class,
            \App\Events\UserRoleRemoved::class,
        ], \App\Listeners\SendRbacSecurityNotification::class);

        // Register Activity Log Event Subscribers (Module 27)
        \Illuminate\Support\Facades\Event::subscribe(\App\Listeners\AuditModelSubscriber::class);
        \Illuminate\Support\Facades\Event::subscribe(\App\Listeners\AuditAuthSubscriber::class);

        // Register API Rate Limiters
        \Illuminate\Support\Facades\RateLimiter::for('api', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('api.auth', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by($request->ip());
        });
    }
}
