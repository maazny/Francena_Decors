<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeSettingsSecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest is redirected to login.
     */
    public function test_guest_cannot_update_theme_settings(): void
    {
        $response = $this->put(route('admin.theme.settings.update'), [
            'primary_color' => '#112233',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test unauthorized user (with a role that lacks permission) is blocked.
     */
    public function test_unauthorized_user_cannot_update_theme_settings(): void
    {
        $user = User::factory()->create();
        
        // Create an unauthorized role
        $role = Role::create([
            'name' => 'regular_user',
            'label' => 'Regular User',
            'is_system' => false,
        ]);
        $user->roles()->attach($role->id);

        $response = $this->actingAs($user)->put(route('admin.theme.settings.update'), [
            'primary_color' => '#112233',
        ]);

        $response->assertStatus(403);
    }

    public function test_authorized_user_can_update_theme_settings(): void
    {
        $user = User::factory()->create();
        
        $role = Role::create([
            'name' => 'super_admin',
            'label' => 'Super Administrator',
            'is_system' => true,
        ]);
        
        $user->roles()->attach($role->id);

        $response = $this->actingAs($user)->put(route('admin.theme.settings.update'), [
            'primary_color' => '#112233',
            'secondary_color' => '#445566',
            'accent_color' => '#778899',
            'background_color' => '#ffffff',
            'surface_color' => '#ffffff',
            'text_color' => '#222222',
            'heading_color' => '#111111',
            'loader_enabled' => true,
            'dark_mode' => false,
            'animation_enabled' => true,
            'status' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('theme_settings', [
            'primary_color' => '#112233',
            'secondary_color' => '#445566',
        ]);
    }
}
