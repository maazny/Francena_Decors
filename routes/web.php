<?php

use App\Http\Controllers\Admin\AboutSectionController;
use App\Http\Controllers\Admin\CompanyTimelineController;
use App\Http\Controllers\Admin\CompanyValueController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FooterSettingController;
use App\Http\Controllers\Admin\HeaderLogoController;
use App\Http\Controllers\Admin\HeaderSettingController;
use App\Http\Controllers\Admin\HeaderTopbarController;
use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\ThemeSettingController;
use App\Http\Controllers\Admin\WhyChooseUsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProjectsController;
use Illuminate\Support\Facades\Route;

Route::put('/admin/theme-settings/update', [ThemeSettingController::class, 'update'])
    ->name('theme.settings.update');

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectsController::class, 'show'])->name('projects.show');
Route::get('/projects/category/{projectCategory:slug}', [ProjectsController::class, 'category'])->name('projects.category');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route(auth()->check() ? 'admin.dashboard' : 'admin.login');
    })->name('index');

    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.submit');

        Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

        Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('media', [MediaController::class, 'index'])->name('media.index');
        Route::get('media/create', [MediaController::class, 'create'])->name('media.create');
        Route::post('media', [MediaController::class, 'store'])->name('media.store');
        Route::get('media/{media}/download', [MediaController::class, 'download'])->name('media.download');
        Route::get('media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
        Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
        Route::post('media/bulk-delete', [MediaController::class, 'bulkDelete'])->name('media.bulk-delete');
        Route::get('media/{media}', [MediaController::class, 'show'])->name('media.show');

        Route::name('theme.settings.')->group(function () {
            Route::get('theme-settings', [ThemeSettingController::class, 'edit'])->name('edit');
            Route::put('theme-settings', [ThemeSettingController::class, 'update'])->name('update');
        });

        Route::name('header.settings.')->group(function () {
            Route::get('header-settings', [HeaderSettingController::class, 'edit'])->name('edit');
            Route::put('header-settings', [HeaderSettingController::class, 'update'])->name('update');
        });

        Route::name('header.topbars.')->group(function () {
            Route::get('header-topbars', [HeaderTopbarController::class, 'edit'])->name('edit');
            Route::put('header-topbars', [HeaderTopbarController::class, 'update'])->name('update');
        });

        Route::name('header.logos.')->group(function () {
            Route::get('header-logos', [HeaderLogoController::class, 'edit'])->name('edit');
            Route::put('header-logos', [HeaderLogoController::class, 'update'])->name('update');
        });

        Route::name('footer.settings.')->group(function () {
            Route::get('footer-settings', [FooterSettingController::class, 'edit'])->name('edit');
            Route::put('footer-settings', [FooterSettingController::class, 'update'])->name('update');
        });

        Route::post('hero-sliders/bulk', [HeroSliderController::class, 'bulk'])->name('hero-sliders.bulk');
        Route::post('hero-sliders/reorder', [HeroSliderController::class, 'reorder'])->name('hero-sliders.reorder');
        Route::post('hero-sliders/{heroSlider}/toggle-status', [HeroSliderController::class, 'toggleStatus'])->name('hero-sliders.toggle-status');
        Route::post('hero-sliders/{heroSlider}/duplicate', [HeroSliderController::class, 'duplicate'])->name('hero-sliders.duplicate');
        Route::post('hero-sliders/{heroSlider}/restore', [HeroSliderController::class, 'restore'])->name('hero-sliders.restore');
        Route::resource('hero-sliders', HeroSliderController::class);

        Route::get('about-sections', [AboutSectionController::class, 'edit'])->name('about-sections.edit');
        Route::put('about-sections', [AboutSectionController::class, 'update'])->name('about-sections.update');
        Route::resource('company-values', CompanyValueController::class)->except(['index', 'show']);
        Route::resource('company-timelines', CompanyTimelineController::class)->except(['index', 'show']);
        Route::resource('why-choose-us', WhyChooseUsController::class)->except(['index', 'show']);

        Route::get('site-settings', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
        Route::put('site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');

        Route::post('project-categories/bulk', [ProjectCategoryController::class, 'bulk'])->name('project-categories.bulk');
        Route::post('project-categories/{projectCategory}/toggle-status', [ProjectCategoryController::class, 'toggleStatus'])->name('project-categories.toggle-status');
        Route::post('project-categories/{projectCategory}/restore', [ProjectCategoryController::class, 'restore'])->name('project-categories.restore');
        Route::resource('project-categories', ProjectCategoryController::class)->except(['show']);

        Route::post('projects/bulk', [ProjectController::class, 'bulk'])->name('projects.bulk');
        Route::post('projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
        Route::post('projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
        Route::post('projects/{project}/duplicate', [ProjectController::class, 'duplicate'])->name('projects.duplicate');
        Route::resource('projects', ProjectController::class)->except(['show']);

        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    });
});
