<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\BackupHistory;
use App\Models\NewsletterSubscriber;
use App\Models\Contact;
use App\Services\ActivityLogService;
use App\Http\Resources\Api\V1\DashboardResource;
use Illuminate\Http\JsonResponse;

class DashboardController extends ApiController
{
    /**
     * @var ActivityLogService
     */
    protected $activityLogger;

    /**
     * DashboardController constructor.
     */
    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Get system-wide operational metrics and recent activities for the dashboard.
     */
    public function index(): JsonResponse
    {
        $stats = [
            'users_count' => User::count(),
            'roles_count' => Role::count(),
            'permissions_count' => Permission::count(),
            'backups_count' => BackupHistory::count(),
            'newsletter_subscribers_count' => NewsletterSubscriber::count(),
            'contacts_count' => Contact::count(),
            'recent_activities' => $this->activityLogger->getRecentLogs(5),
            'recent_contacts' => Contact::latest()->limit(5)->get(),
        ];

        return $this->success(new DashboardResource($stats), 'Dashboard stats retrieved successfully');
    }
}
