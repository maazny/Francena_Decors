<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Services\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermissionController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display permissions list.
     */
    public function index(): View
    {
        $permissions = Permission::with('group')->paginate(20);
        return view('admin.rbac.permissions.index', compact('permissions'));
    }

    /**
     * Show permission wizard.
     */
    public function create(): View
    {
        $groups = PermissionGroup::all();
        return view('admin.rbac.permissions.create', compact('groups'));
    }

    /**
     * Store permission.
     */
    public function store(StorePermissionRequest $request): RedirectResponse
    {
        $this->permissionService->createPermission($request->validated());

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Security gate authorization permission created successfully.');
    }

    /**
     * Show permission editor.
     */
    public function edit(Permission $permission): View
    {
        $groups = PermissionGroup::all();
        return view('admin.rbac.permissions.edit', compact('permission', 'groups'));
    }

    /**
     * Update permission parameters.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $this->permissionService->updatePermission($permission, $request->validated());

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Security gate authorization permission updated successfully.');
    }

    /**
     * Delete permission.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Security gate authorization permission deleted successfully.');
    }
}
