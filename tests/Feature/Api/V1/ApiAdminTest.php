<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\BackupHistory;
use App\Models\ActivityLog;
use Laravel\Sanctum\Sanctum;

class ApiAdminTest extends TestCase
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
            'name' => 'Super Admin User',
            'email' => 'admin@example.com',
        ]);

        $this->adminUser->roles()->sync([$adminRole->id]);
    }

    /**
     * Test Dashboard metrics endpoint.
     */
    public function test_dashboard_endpoint_access_for_admin(): void
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson('/api/v1/dashboard');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure(['data' => ['users_count', 'roles_count', 'recent_activities']]);
    }

    /**
     * Test User Management CRUD.
     */
    public function test_user_management_crud(): void
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson('/api/v1/users');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $response = $this->postJson('/api/v1/users', [
            'name' => 'New User',
            'email' => 'new.user@example.com',
            'password' => 'securePassword123',
        ]);
        $response->assertStatus(201);
        $response->assertJsonPath('data.name', 'New User');

        $userId = $response->json('data.id');

        $response = $this->putJson("/api/v1/users/{$userId}", [
            'name' => 'Updated User',
            'email' => 'new.user@example.com',
        ]);
        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'Updated User');

        $response = $this->deleteJson("/api/v1/users/{$userId}");
        $response->assertStatus(200);
    }

    /**
     * Test Role and Permission endpoints.
     */
    public function test_role_and_permission_endpoints(): void
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson('/api/v1/roles');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $response = $this->postJson('/api/v1/roles', [
            'name' => 'content_editor',
            'label' => 'Content Editor',
            'description' => 'Edits articles',
        ]);
        $response->assertStatus(201);
        $response->assertJsonPath('data.name', 'content_editor');

        $response = $this->getJson('/api/v1/permissions');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    /**
     * Test Backups management.
     */
    public function test_backup_history_and_restoration(): void
    {
        Sanctum::actingAs($this->adminUser);

        $backup = BackupHistory::create([
            'backup_name' => 'Test Backup Archive',
            'backup_type' => \App\Enums\BackupType::DATABASE,
            'storage_disk' => 'local',
            'storage_path' => 'backups/test.zip',
            'file_name' => 'test.zip',
            'file_size' => 1024,
            'status' => \App\Enums\BackupStatus::COMPLETED,
            'checksum' => 'testchecksum',
        ]);

        $response = $this->getJson('/api/v1/backups');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [['id', 'backup_name', 'status']]]);

        $response = $this->postJson("/api/v1/backups/{$backup->id}/verify");
        $response->assertStatus(200);
        $response->assertJsonPath('data.status', 'corrupt');
    }

    /**
     * Test Activity Logs endpoints.
     */
    public function test_activity_logs_indexing(): void
    {
        Sanctum::actingAs($this->adminUser);

        ActivityLog::create([
            'module' => 'users',
            'action' => \App\Enums\ActivityAction::CREATE,
            'description' => 'User created log test',
            'status' => \App\Enums\ActivityStatus::SUCCESS,
        ]);

        $response = $this->getJson('/api/v1/activity-logs');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [['id', 'module', 'action']]]);
    }
}
