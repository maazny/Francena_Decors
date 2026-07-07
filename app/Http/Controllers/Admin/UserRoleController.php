<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRoleRequest;
use App\Models\User;
use App\Models\Role;
use App\Services\UserRoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserRoleController extends Controller
{
    protected UserRoleService $userRoleService;

    public function __construct(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
    }

    /**
     * Display users list showing assigned authorization roles.
     */
    public function index(): View
    {
        $users = User::with('roles')->paginate(20);
        return view('admin.rbac.users.index', compact('users'));
    }

    /**
     * Show editor to assign role tiers to user.
     */
    public function edit(User $user): View
    {
        $roles = Role::all();
        $userRoles = $user->roles()->pluck('role_id')->toArray();

        return view('admin.rbac.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Synchronize assigned user role tiers.
     */
    public function update(AssignRoleRequest $request, User $user): RedirectResponse
    {
        $roleIds = $request->input('role_ids', []);
        
        $this->userRoleService->syncRoles($user, $roleIds);

        return redirect()->route('admin.users-roles.index')
            ->with('success', 'User authorization role assignments synchronized successfully.');
    }
}
