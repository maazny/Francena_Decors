<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Media;
use App\Models\BackupHistory;
use App\Models\ActivityLog;
use Laravel\Sanctum\Sanctum;

class ApiV3EndpointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    protected $adminUser;

    /**
     * Setup test requirements.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'super_admin', 'label' => 'Super Admin', 'is_system' => true]);

        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin.test@example.com',
        ]);

        $this->adminUser->roles()->sync([$adminRole->id]);
    }

    /**
     * Test authenticated notifications list and mark read.
     */
    public function test_authenticated_notifications_endpoints(): void
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson('/api/v1/notifications');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $response = $this->putJson('/api/v1/notifications/read', [
            'ids' => []
        ]);
        $response->assertStatus(200);
    }

    /**
     * Test active tokens management.
     */
    public function test_authenticated_tokens_endpoints(): void
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson('/api/v1/tokens');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    /**
     * Test bulk user operations and export.
     */
    public function test_users_bulk_actions_and_export(): void
    {
        Sanctum::actingAs($this->adminUser);

        $otherUser = User::factory()->create();

        $response = $this->postJson('/api/v1/users/bulk-status', [
            'ids' => [$otherUser->id],
            'status' => false
        ]);
        $response->assertStatus(200);

        $response = $this->postJson('/api/v1/users/bulk-delete', [
            'ids' => [$otherUser->id]
        ]);
        $response->assertStatus(200);

        $response = $this->getJson('/api/v1/users/export');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    /**
     * Test bulk role operations and export.
     */
    public function test_roles_bulk_actions_and_export(): void
    {
        Sanctum::actingAs($this->adminUser);

        $role = Role::create(['name' => 'temp_role', 'label' => 'Temp Role', 'is_system' => false]);

        $response = $this->postJson('/api/v1/roles/bulk-delete', [
            'ids' => [$role->id]
        ]);
        $response->assertStatus(200);

        $response = $this->getJson('/api/v1/roles/export');
        $response->assertStatus(200);
    }

    /**
     * Test sparse fieldsets filtering.
     */
    public function test_sparse_fieldsets_filtering(): void
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson('/api/v1/users?fields=name');
        $response->assertStatus(200);
        $this->assertArrayHasKey('name', $response->json('data.0'));
        $this->assertArrayNotHasKey('email', $response->json('data.0'));
    }
}
