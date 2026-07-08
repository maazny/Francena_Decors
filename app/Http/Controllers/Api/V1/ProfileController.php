<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\ApiProfileUpdateRequest;
use App\Http\Requests\Api\ApiChangePasswordRequest;
use App\Http\Resources\Api\V1\ProfileResource;
use App\Services\ActivityLogService;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ProfileController extends ApiController
{
    /**
     * @var ActivityLogService
     */
    protected $activityLogger;

    /**
     * ProfileController constructor.
     */
    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display current authenticated user profile attributes.
     */
    public function show(Request $request): JsonResponse
    {
        return $this->success(new ProfileResource($request->user()), 'Profile retrieved successfully');
    }

    /**
     * Update user details (name, email) in the database.
     */
    public function update(ApiProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $oldValues = $user->only(['name', 'email']);
        $user->update($request->only(['name', 'email']));
        $newValues = $user->only(['name', 'email']);

        $this->activityLogger->log([
            'user_id' => $user->id,
            'module' => 'profile',
            'action' => ActivityAction::UPDATE,
            'description' => 'Updated profile details via API',
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'status' => ActivityStatus::SUCCESS,
        ]);

        return $this->success(new ProfileResource($user), 'Profile updated successfully');
    }

    /**
     * Change user account password securely.
     */
    public function changePassword(ApiChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $this->activityLogger->log([
            'user_id' => $user->id,
            'module' => 'profile',
            'action' => ActivityAction::UPDATE,
            'description' => 'Changed password via API',
            'status' => ActivityStatus::SUCCESS,
        ]);

        return $this->success(null, 'Password changed successfully');
    }
}
