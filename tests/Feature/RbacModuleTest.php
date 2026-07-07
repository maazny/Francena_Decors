<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\PermissionGroup;
use App\Models\Permission;
use Database\Seeders\RbacSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test role assignments and relation mapping.
     */
    public function test_user_roles_relation_mapping(): void
    {
        $user = User::factory()->create();
        
        $role = Role::create([
            'name' => 'editor',
            'label' => 'Editor User',
            'is_system' => false,
        ]);

        $user->roles()->attach($role->id);

        $this->assertTrue($user->hasRole('editor'));
        $this->assertFalse($user->hasRole('super_admin'));
    }

    /**
     * Test super admin bypass.
     */
    public function test_super_admin_bypass_authorizes_everything(): void
    {
        $user = User::factory()->create();

        $role = Role::create([
            'name' => 'super_admin',
            'label' => 'Super Admin Lock',
            'is_system' => true,
        ]);

        $user->roles()->attach($role->id);

        // Super admin bypass should immediately return true for any random permission
        $this->assertTrue($user->hasPermission('view_restricted_widgets'));
        $this->assertTrue($user->hasPermission('delete_system_database'));
    }

    /**
     * Test normal role-based action permission matching.
     */
    public function test_normal_role_permission_matching(): void
    {
        $user = User::factory()->create();

        $role = Role::create([
            'name' => 'editor',
            'label' => 'Editor User',
            'is_system' => false,
        ]);

        $group = PermissionGroup::create([
            'name' => 'Blog Module',
        ]);

        $perm = Permission::create([
            'permission_group_id' => $group->id,
            'name' => 'publish_blog',
            'label' => 'Publish Blog Posts',
        ]);

        $role->permissions()->attach($perm->id);
        $user->roles()->attach($role->id);

        $this->assertTrue($user->hasPermission('publish_blog'));
        $this->assertFalse($user->hasPermission('delete_blog'));
    }

    /**
     * Test direct permission overrides bypassing roles.
     */
    public function test_direct_permission_overrides(): void
    {
        $user = User::factory()->create();

        $group = PermissionGroup::create([
            'name' => 'SEO Module',
        ]);

        $perm = Permission::create([
            'permission_group_id' => $group->id,
            'name' => 'configure_seo',
            'label' => 'Configure SEO settings',
        ]);

        $user->directPermissions()->attach($perm->id);

        // Has no roles but holds direct override
        $this->assertTrue($user->hasPermission('configure_seo'));
        $this->assertFalse($user->hasPermission('view_seo'));
    }

    /**
     * Test that RBAC seeders execute idempotently.
     */
    public function test_rbac_seeder_runs_idempotently(): void
    {
        $this->seed(RbacSeeder::class);
        $countGroup = PermissionGroup::count();
        $countPerm = Permission::count();
        $countRole = Role::count();

        // Seed again
        $this->seed(RbacSeeder::class);

        $this->assertEquals($countGroup, PermissionGroup::count());
        $this->assertEquals($countPerm, Permission::count());
        $this->assertEquals($countRole, Role::count());
    }

    /**
     * Test system roles protection triggers validation exception.
     */
    public function test_system_role_deletion_blocked(): void
    {
        $role = Role::create([
            'name' => 'super_admin',
            'label' => 'Super Administrator',
            'is_system' => true,
        ]);

        $service = new \App\Services\RoleService();

        $this->expectException(\InvalidArgumentException::class);
        $service->deleteRole($role);
    }

    /**
     * Test authorization permission matrix compiles structure.
     */
    public function test_matrix_generation_matches_schema(): void
    {
        $this->seed(RbacSeeder::class);

        $service = new \App\Services\AuthorizationService();
        $matrix = $service->getPermissionMatrix();

        $this->assertArrayHasKey('roles', $matrix);
        $this->assertArrayHasKey('matrix', $matrix);
    }

    /**
     * Test admin can access RBAC dashboard.
     */
    public function test_admin_can_access_rbac_dashboard(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.rbac.dashboard'));
        $response->assertStatus(200);
    }

    /**
     * Test admin can access roles index.
     */
    public function test_admin_can_access_roles_index(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.roles.index'));
        $response->assertStatus(200);
    }
}
