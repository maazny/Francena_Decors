<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Permission;
use App\Models\Role;
use App\Services\PermissionService;
use App\Http\Resources\Api\V1\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class PermissionController extends ApiController
{
    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * PermissionController constructor.
     */
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of permissions.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Permission::with('group');

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('guard_name', 'like', $search);
            });
        }

        $this->applyFilters($query, [
            'group_id' => 'group_id',
        ]);

        $this->applySorting($query, ['name', 'created_at'], 'name');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, PermissionResource::class, 'Permissions retrieved successfully');
    }

    /**
     * Display details of specific permission.
     */
    public function show(int $id): JsonResponse
    {
        $permission = Permission::with('group')->findOrFail($id);
        return $this->success(new PermissionResource($permission), 'Permission details retrieved successfully');
    }

    /**
     * Create a new permission.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'guard_name' => ['required', 'string', 'max:255'],
            'group_id' => ['nullable', 'exists:permission_groups,id'],
        ]);

        $permission = $this->permissionService->createPermission($validated);

        return $this->created(new PermissionResource($permission), 'Permission created successfully');
    }

    /**
     * Update details of specific permission.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'guard_name' => ['required', 'string', 'max:255'],
            'group_id' => ['nullable', 'exists:permission_groups,id'],
        ]);

        $this->permissionService->updatePermission($permission, $validated);

        return $this->success(new PermissionResource($permission), 'Permission updated successfully');
    }

    /**
     * Assign permissions list to specific role.
     */
    public function assignToRole(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::findOrFail($validated['role_id']);
        $this->permissionService->assignPermissionsToRole($role, $validated['permissions']);

        return $this->success(null, 'Permissions assigned to role successfully');
    }
}
