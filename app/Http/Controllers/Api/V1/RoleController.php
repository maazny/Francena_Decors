<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Role;
use App\Services\RoleService;
use App\Http\Resources\Api\V1\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class RoleController extends ApiController
{
    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * RoleController constructor.
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of system roles.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Role::with('permissions');

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('label', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
        }

        $this->applySorting($query, ['name', 'label', 'created_at'], 'name');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, RoleResource::class, 'Roles retrieved successfully');
    }

    /**
     * Display specific role details.
     */
    public function show(int $id): JsonResponse
    {
        $role = Role::with('permissions')->findOrFail($id);
        return $this->success(new RoleResource($role), 'Role details retrieved successfully');
    }

    /**
     * Create a new role and sync permissions.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'label' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = $this->roleService->createRole($validated);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
            $this->roleService->clearCache();
        }

        return $this->created(new RoleResource($role->load('permissions')), 'Role created successfully');
    }

    /**
     * Update role details and permissions list.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'label' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        try {
            $this->roleService->updateRole($role, $validated);

            if (isset($validated['permissions'])) {
                $role->permissions()->sync($validated['permissions']);
                $this->roleService->clearCache();
            }

            return $this->success(new RoleResource($role->load('permissions')), 'Role updated successfully');
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    /**
     * Delete a role.
     */
    public function destroy(int $id): JsonResponse
    {
        $role = Role::findOrFail($id);

        try {
            $this->roleService->deleteRole($role);
            return $this->success(null, 'Role deleted successfully');
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
