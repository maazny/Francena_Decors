<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\ApiLoginRequest;
use App\Http\Requests\Api\ApiProfileUpdateRequest;
use App\Http\Requests\Api\ApiChangePasswordRequest;
use App\Http\Resources\Api\V1\ProfileResource;
use App\Services\ActivityLogService;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
    /**
     * @var ActivityLogService
     */
    protected $activityLogger;

    /**
     * AuthController constructor.
     */
    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Authenticate user and issue personal access token.
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $this->activityLogger->log([
                'user_id' => $user ? $user->id : null,
                'module' => 'auth',
                'action' => ActivityAction::LOGIN,
                'description' => 'Failed API login attempt for ' . $request->email,
                'status' => ActivityStatus::FAILED,
            ]);

            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        if (isset($user->status) && !$user->status) {
            $this->activityLogger->log([
                'user_id' => $user->id,
                'module' => 'auth',
                'action' => ActivityAction::LOGIN,
                'description' => 'Blocked API login attempt for deactivated user ' . $user->email,
                'status' => ActivityStatus::FAILED,
            ]);

            return $this->forbidden('Your account is deactivated.');
        }

        $deviceName = $request->input('device_name', $request->userAgent() ?: 'Unknown Device');
        $token = $user->createToken($deviceName);

        $this->activityLogger->log([
            'user_id' => $user->id,
            'module' => 'auth',
            'action' => ActivityAction::LOGIN,
            'description' => 'Successful API login from ' . $deviceName,
            'status' => ActivityStatus::SUCCESS,
        ]);

        return $this->success([
            'token' => $token->plainTextToken,
            'user' => new ProfileResource($user),
        ], 'Login successful');
    }

    /**
     * Revoke current active access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $currentAccessToken = $user->currentAccessToken();
            if ($currentAccessToken) {
                $currentAccessToken->delete();
            }

            $this->activityLogger->log([
                'user_id' => $user->id,
                'module' => 'auth',
                'action' => ActivityAction::LOGOUT,
                'description' => 'User logged out from current device',
                'status' => ActivityStatus::SUCCESS,
            ]);
        }

        return $this->success(null, 'Logged out successfully');
    }

    /**
     * Revoke all access tokens for the authenticated user.
     */
    public function logoutAllDevices(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();

            $this->activityLogger->log([
                'user_id' => $user->id,
                'module' => 'auth',
                'action' => ActivityAction::LOGOUT,
                'description' => 'User logged out from all devices',
                'status' => ActivityStatus::SUCCESS,
            ]);
        }

        return $this->success(null, 'Logged out from all devices successfully');
    }

    /**
     * Get authenticated user profile details.
     */
    public function profile(Request $request): JsonResponse
    {
        return $this->success(new ProfileResource($request->user()), 'Profile retrieved successfully');
    }

    /**
     * Update authenticated user profile details.
     */
    public function updateProfile(ApiProfileUpdateRequest $request): JsonResponse
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
     * Change authenticated user password.
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

    /**
     * Revoke a specific personal access token.
     */
    public function revokeToken(Request $request): JsonResponse
    {
        $request->validate([
            'token_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $token = $user->tokens()->find($request->token_id);

        if (!$token) {
            return $this->notFound('Token not found or does not belong to you');
        }

        $tokenName = $token->name;
        $token->delete();

        $this->activityLogger->log([
            'user_id' => $user->id,
            'module' => 'auth',
            'action' => ActivityAction::DELETE,
            'description' => "Revoked API token '{$tokenName}'",
            'status' => ActivityStatus::SUCCESS,
        ]);

        return $this->success(null, 'Token revoked successfully');
    }
}
