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
    public function edit(User $users_role): View
    {
        $roles = Role::all();
        $userRoles = $users_role->roles()->pluck('role_id')->toArray();

        return view('admin.rbac.users.edit', [
            'user' => $users_role,
            'roles' => $roles,
            'userRoles' => $userRoles,
        ]);
    }

    public function update(AssignRoleRequest $request, User $users_role): RedirectResponse
    {
        $roleIds = $request->input('role_ids', []);
        
        // Self-lockout safeguard prevention
        if ($users_role->id === auth()->id()) {
            $currentRoles = $users_role->roles()->pluck('roles.id', 'name')->toArray();
            
            if (isset($currentRoles['super_admin'])) {
                $superAdminId = $currentRoles['super_admin'];
                if (!in_array($superAdminId, $roleIds)) {
                    return redirect()->back()->with('error', 'Self-lockout safeguard: You cannot remove the Super Admin role from your own profile.');
                }
            }

            if (isset($currentRoles['administrator'])) {
                $adminId = $currentRoles['administrator'];
                if (!in_array($adminId, $roleIds)) {
                    return redirect()->back()->with('error', 'Self-lockout safeguard: You cannot remove the Administrator role from your own profile.');
                }
            }
        }
        
        $this->userRoleService->syncRoles($users_role, $roleIds);

        return redirect()->route('admin.users-roles.index')
            ->with('success', 'User authorization role assignments synchronized successfully.');
    }
}
