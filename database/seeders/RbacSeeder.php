<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define Default Roles
        $roles = [
            'super_admin' => [
                'label' => 'Super Administrator',
                'description' => 'Unrestricted access to every system control and settings dashboard.',
                'is_system' => true,
            ],
            'administrator' => [
                'label' => 'Administrator',
                'description' => 'Full administrative access controls except destructive system overrides.',
                'is_system' => true,
            ],
            'manager' => [
                'label' => 'Manager',
                'description' => 'Manages operational departments, team schedules, and leads listings.',
                'is_system' => false,
            ],
            'editor' => [
                'label' => 'Editor',
                'description' => 'Publishes and edits articles, blogs, galleries, and pages overrides.',
                'is_system' => false,
            ],
            'author' => [
                'label' => 'Author',
                'description' => 'Drafts blog articles and custom timeline entries.',
                'is_system' => false,
            ],
            'viewer' => [
                'label' => 'Viewer',
                'description' => 'Read-only access to admin dashboards, metrics, and logs.',
                'is_system' => false,
            ],
        ];

        $roleModels = [];
        foreach ($roles as $name => $meta) {
            $roleModels[$name] = Role::updateOrCreate(
                ['name' => $name],
                [
                    'label' => $meta['label'],
                    'description' => $meta['description'],
                    'is_system' => $meta['is_system'],
                ]
            );
        }

        // 2. Define Default Permission Groups & Mapped Actions
        $modules = [
            'Dashboard' => ['view'],
            'Site Settings' => ['view', 'configure'],
            'Theme Settings' => ['view', 'configure'],
            'Media Library' => ['view', 'create', 'edit', 'delete', 'export'],
            'Header' => ['view', 'edit'],
            'Footer' => ['view', 'edit'],
            'Hero' => ['view', 'create', 'edit', 'delete'],
            'About' => ['view', 'edit'],
            'Services' => ['view', 'create', 'edit', 'delete', 'publish'],
            'Projects' => ['view', 'create', 'edit', 'delete', 'publish'],
            'Gallery' => ['view', 'create', 'edit', 'delete'],
            'Testimonials' => ['view', 'create', 'edit', 'delete'],
            'Team' => ['view', 'create', 'edit', 'delete'],
            'Clients' => ['view', 'create', 'edit', 'delete'],
            'FAQ' => ['view', 'create', 'edit', 'delete'],
            'Blog' => ['view', 'create', 'edit', 'delete', 'publish'],
            'Careers' => ['view', 'create', 'edit', 'delete', 'publish', 'approve'],
            'Contact' => ['view', 'edit', 'delete', 'assign'],
            'Newsletter' => ['view', 'create', 'edit', 'delete', 'publish', 'export'],
            'SEO' => ['view', 'create', 'edit', 'delete', 'configure'],
            'Users' => ['view', 'create', 'edit', 'delete'],
            'Roles' => ['view', 'create', 'edit', 'delete', 'configure'],
        ];

        $allPermissions = [];
        foreach ($modules as $groupName => $actions) {
            $group = PermissionGroup::updateOrCreate(
                ['name' => $groupName],
                ['description' => "Permissions for managing {$groupName} components."]
            );

            foreach ($actions as $act) {
                // E.g. view_blog, edit_seo, configure_site_settings
                $normalizedGroup = strtolower(str_replace(' ', '_', $groupName));
                $permissionKey = "{$act}_{$normalizedGroup}";
                $permissionLabel = ucfirst($act) . " " . $groupName;

                $perm = Permission::updateOrCreate(
                    ['name' => $permissionKey],
                    [
                        'permission_group_id' => $group->id,
                        'label' => $permissionLabel,
                        'description' => "Allows user to {$act} {$groupName} items.",
                    ]
                );

                $allPermissions[] = $perm->id;
            }
        }

        // 3. Assign All Permissions to Administrator
        $roleModels['administrator']->permissions()->sync($allPermissions);

        // Assign read-only view permissions to Viewer role
        $viewPermissions = Permission::where('name', 'like', 'view_%')->pluck('id')->toArray();
        $roleModels['viewer']->permissions()->sync($viewPermissions);
    }
}
