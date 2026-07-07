<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Services\RoleService;
use App\Services\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    protected RoleService $roleService;
    protected PermissionService $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    /**
     * Display listing of roles.
     */
    public function index(): View
    {
        $roles = Role::withCount('users')->paginate(20);
        return view('admin.rbac.roles.index', compact('roles'));
    }

    /**
     * Show role creation wizard.
     */
    public function create(): View
    {
        return view('admin.rbac.roles.create');
    }

    /**
     * Store role.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $this->roleService->createRole($request->validated());

        return redirect()->route('admin.roles.index')
            ->with('success', 'User authorization role created successfully.');
    }

    /**
     * Show role editor.
     */
    public function edit(Role $role): View
    {
        return view('admin.rbac.roles.edit', compact('role'));
    }

    /**
     * Update role.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        try {
            $this->roleService->updateRole($role, $request->validated());
            return redirect()->route('admin.roles.index')
                ->with('success', 'User authorization role updated successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['name' => $e->getMessage()]);
        }
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        try {
            $this->roleService->deleteRole($role);
            return redirect()->route('admin.roles.index')
                ->with('success', 'User authorization role deleted successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show permission matrix editor for a specific role.
     */
    public function editPermissions(Role $role): View
    {
        $rolePermissions = $role->permissions()->pluck('permission_id')->toArray();
        $groups = \App\Models\PermissionGroup::with('permissions')->get();

        return view('admin.rbac.roles.permissions', compact('role', 'rolePermissions', 'groups'));
    }

    /**
     * Update permissions bindings for a specific role.
     */
    public function updatePermissions(Request $request, Role $role): RedirectResponse
    {
        $permissionIds = $request->input('permission_ids', []);
        
        $this->permissionService->assignPermissionsToRole($role, $permissionIds);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Authorization permissions synchronized for this role tier successfully.');
    }
}
