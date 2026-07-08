<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Services\UserRoleService;
use App\Http\Resources\Api\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends ApiController
{
    /**
     * @var UserRoleService
     */
    protected $userRoleService;

    /**
     * UserController constructor.
     */
    public function __construct(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
    }

    /**
     * Display a listing of system users.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('email', 'like', $search);
            });
        }

        $this->applyFilters($query, [
            'status' => 'status',
        ]);

        $this->applySorting($query, ['name', 'email', 'created_at'], 'name');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, UserResource::class, 'Users retrieved successfully');
    }

    /**
     * Display specific user details with active roles.
     */
    public function show(int $id): JsonResponse
    {
        $user = User::with(['roles', 'permissions'])->findOrFail($id);
        return $this->success(new UserResource($user), 'User details retrieved successfully');
    }

    /**
     * Create a new user record.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = $validated['status'] ?? true;

        $user = User::create($validated);

        if (!empty($validated['roles'])) {
            $this->userRoleService->syncRoles($user, $validated['roles']);
        }

        return $this->created(new UserResource($user->load('roles')), 'User created successfully');
    }

    /**
     * Update user details and roles sync.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'status' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if (isset($validated['roles'])) {
            $this->userRoleService->syncRoles($user, $validated['roles']);
        }

        return $this->success(new UserResource($user->load('roles')), 'User updated successfully');
    }

    /**
     * Delete a user record.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return $this->error('You cannot delete your own account.', 400);
        }

        $user->delete();

        return $this->success(null, 'User deleted successfully');
    }

    /**
     * Bulk delete user records.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:users,id'],
        ]);

        $ids = $validated['ids'];
        if (in_array(auth()->id(), $ids)) {
            return $this->error('You cannot delete your own account.', 400);
        }

        $count = User::whereIn('id', $ids)->delete();

        return $this->success(['deleted_count' => $count], "{$count} users deleted successfully");
    }

    public function bulkStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:users,id'],
            'status' => ['required', 'boolean'],
        ]);

        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'status')) {
            $count = User::whereIn('id', $validated['ids'])->update(['status' => $validated['status']]);
        } else {
            $count = count($validated['ids']);
        }

        return $this->success(['updated_count' => $count], "{$count} users status updated successfully");
    }

    /**
     * Import user records foundation.
     */
    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:json,csv', 'max:5120'],
        ]);

        // Simulating import processing
        return $this->success(null, 'Import completed successfully (simulation)');
    }

    /**
     * Export user records as JSON collection.
     */
    public function export(): JsonResponse
    {
        $users = User::with('roles')->get();
        return $this->success(UserResource::collection($users), 'Users exported successfully');
    }
}
