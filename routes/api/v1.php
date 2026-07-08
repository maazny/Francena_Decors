<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\AboutController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\GalleryController;
use App\Http\Controllers\Api\V1\TestimonialController;
use App\Http\Controllers\Api\V1\TeamController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\CareerController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\NewsletterController;
use App\Http\Controllers\Api\V1\SeoController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\MediaController;
use App\Http\Controllers\Api\V1\BackupController;
use App\Http\Controllers\Api\V1\ActivityLogController;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:api'])->group(function () {

    // --- PUBLIC ENDPOINTS ---
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/hero', [HomeController::class, 'hero']);
    Route::get('/about', [AboutController::class, 'index']);
    
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{slug}', [ServiceController::class, 'show']);
    
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{slug}', [ProjectController::class, 'show']);
    
    Route::get('/gallery', [GalleryController::class, 'index']);
    Route::get('/testimonials', [TestimonialController::class, 'index']);
    Route::get('/team', [TeamController::class, 'index']);
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/faq', [FaqController::class, 'index']);
    
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/{slug}', [BlogController::class, 'show']);
    
    Route::get('/careers', [CareerController::class, 'index']);
    Route::get('/careers/{slug}', [CareerController::class, 'show']);
    
    Route::get('/seo/{page}', [SeoController::class, 'show']);
    Route::get('/settings', [SettingController::class, 'index']);
    
    Route::post('/newsletter', [NewsletterController::class, 'store']);
    Route::post('/contact', [ContactController::class, 'store']);

    // --- AUTHENTICATION ---
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:api.auth');

    // --- AUTHENTICATED ENDPOINTS ---
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all-devices', [AuthController::class, 'logoutAllDevices']);
        
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
        Route::post('/tokens/revoke', [AuthController::class, 'revokeToken']);

        // --- ADMIN API ENDPOINTS (RBAC PROTECTED) ---
        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('can:view_dashboard');

        // Users Management
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->middleware('can:view_users');
            Route::get('/{id}', [UserController::class, 'show'])->middleware('can:view_users');
            Route::post('/', [UserController::class, 'store'])->middleware('can:create_users');
            Route::put('/{id}', [UserController::class, 'update'])->middleware('can:edit_users');
            Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('can:delete_users');
        });

        // Roles Management
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->middleware('can:view_roles');
            Route::get('/{id}', [RoleController::class, 'show'])->middleware('can:view_roles');
            Route::post('/', [RoleController::class, 'store'])->middleware('can:create_roles');
            Route::put('/{id}', [RoleController::class, 'update'])->middleware('can:edit_roles');
            Route::delete('/{id}', [RoleController::class, 'destroy'])->middleware('can:delete_roles');
        });

        // Permissions Management
        Route::prefix('permissions')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->middleware('can:view_roles');
            Route::get('/{id}', [PermissionController::class, 'show'])->middleware('can:view_roles');
            Route::post('/', [PermissionController::class, 'store'])->middleware('can:create_roles');
            Route::put('/{id}', [PermissionController::class, 'update'])->middleware('can:edit_roles');
            Route::post('/assign', [PermissionController::class, 'assignToRole'])->middleware('can:configure_roles');
        });

        // Media Management
        Route::prefix('media')->group(function () {
            Route::get('/', [MediaController::class, 'index'])->middleware('can:view_media_library');
            Route::post('/upload', [MediaController::class, 'upload'])->middleware('can:create_media_library');
            Route::delete('/{id}', [MediaController::class, 'destroy'])->middleware('can:delete_media_library');
        });

        // Backup & Restore Management
        Route::prefix('backups')->group(function () {
            Route::get('/', [BackupController::class, 'index'])->middleware('can:backup.view');
            Route::get('/{id}', [BackupController::class, 'show'])->middleware('can:backup.view');
            Route::post('/', [BackupController::class, 'store'])->middleware('can:backup.create');
            Route::post('/{id}/restore', [BackupController::class, 'restore'])->middleware('can:backup.restore');
            Route::post('/{id}/verify', [BackupController::class, 'verify'])->middleware('can:backup.verify');
            Route::delete('/{id}', [BackupController::class, 'destroy'])->middleware('can:backup.delete');
        });

        // Activity Logging Logs
        Route::prefix('activity-logs')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->middleware('can:view_activity_logs');
            Route::get('/{id}', [ActivityLogController::class, 'show'])->middleware('can:view_activity_logs');
        });
    });
});
