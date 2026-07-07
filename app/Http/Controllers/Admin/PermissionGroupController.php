<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionGroupController extends Controller
{
    /**
     * Display groups.
     */
    public function index(): View
    {
        $groups = PermissionGroup::withCount('permissions')->paginate(20);
        return view('admin.rbac.groups.index', compact('groups'));
    }

    /**
     * Show creation wizard.
     */
    public function create(): View
    {
        return view('admin.rbac.groups.create');
    }

    /**
     * Store group.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permission_groups,name',
            'description' => 'nullable|string',
        ]);

        PermissionGroup::create($request->only('name', 'description'));

        return redirect()->route('admin.permission-groups.index')
            ->with('success', 'Permission module group created successfully.');
    }

    /**
     * Show editor.
     */
    public function edit(PermissionGroup $permissionGroup): View
    {
        return view('admin.rbac.groups.edit', ['group' => $permissionGroup]);
    }

    /**
     * Update group.
     */
    public function update(Request $request, PermissionGroup $permissionGroup): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permission_groups,name,' . $permissionGroup->id,
            'description' => 'nullable|string',
        ]);

        $permissionGroup->update($request->only('name', 'description'));

        return redirect()->route('admin.permission-groups.index')
            ->with('success', 'Permission module group updated successfully.');
    }

    /**
     * Delete group.
     */
    public function destroy(PermissionGroup $permissionGroup): RedirectResponse
    {
        $permissionGroup->delete();

        return redirect()->route('admin.permission-groups.index')
            ->with('success', 'Permission module group deleted successfully.');
    }
}
