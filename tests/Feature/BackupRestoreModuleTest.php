<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\BackupHistory;
use App\Models\BackupSchedule;
use App\Enums\BackupType;
use App\Enums\BackupStatus;
use App\Enums\BackupFrequency;
use App\Contracts\BackupServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupRestoreModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected BackupServiceInterface $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();

        // Bind super admin role
        $superAdminRole = Role::create([
            'name' => 'super_admin',
            'label' => 'Super Administrator',
            'is_system' => true,
        ]);
        $this->admin->roles()->attach($superAdminRole->id);

        $this->service = app(BackupServiceInterface::class);

        // Fake storage to prevent writing actual files to server during test suite runs
        Storage::fake('local');

        // Disable queues in testing by default
        config(['backup.queue_enabled' => false]);
    }

    /**
     * Test zipping compilation and db vacuuming flows.
     */
    public function test_service_can_create_backup(): void
    {
        // Disable queues to run backup synchronously inside this test
        config(['backup.queue_enabled' => false]);

        $backup = $this->service->createBackup([
            'backup_name' => 'Test Database Backup',
            'backup_type' => BackupType::DATABASE,
            'description' => 'Test backup description.',
            'created_by' => $this->admin->id,
        ]);

        $this->assertNotNull($backup);
        $this->assertDatabaseHas('backup_histories', [
            'id' => $backup->id,
            'status' => 'completed',
            'backup_type' => 'database',
        ]);

        // Assert file actually created in faked storage
        Storage::disk('local')->assertExists($backup->storage_path);
    }

    /**
     * Test checksum validation.
     */
    public function test_service_verifies_checksums(): void
    {
        config(['backup.queue_enabled' => false]);

        $backup = $this->service->createBackup([
            'backup_name' => 'Verification Checksum Test',
            'backup_type' => BackupType::DATABASE,
            'created_by' => $this->admin->id,
        ]);

        $verified = $this->service->verifyBackup($backup);
        $this->assertTrue($verified);
        $this->assertEquals(1, $backup->fresh()->is_verified);
    }

    /**
     * Test statistics metrics calculations.
     */
    public function test_statistics_aggregation(): void
    {
        config(['backup.queue_enabled' => false]);
        Cache::forget('backups:statistics');

        $this->service->createBackup([
            'backup_name' => 'Stats Test 1',
            'backup_type' => BackupType::DATABASE,
            'created_by' => $this->admin->id,
        ]);

        $stats = $this->service->getStatistics();

        $this->assertEquals(1, $stats['total_backups']);
        $this->assertEquals(1, $stats['database_backups']);
    }

    /**
     * Test controller routes accessibility.
     */
    public function test_admin_routes_accessibility(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.backups.index'));
        $response->assertOk();
        $response->assertSee('History');

        $backup = $this->service->createBackup([
            'backup_name' => 'Details View Test',
            'backup_type' => BackupType::DATABASE,
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.backups.show', $backup->id));
        $response->assertOk();
        $response->assertSee('Archive Name');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.backup-schedules.index'));
        $response->assertOk();
        $response->assertSee('Schedule Timers');
    }

    /**
     * Test scheduled configuration endpoints.
     */
    public function test_schedule_management_endpoints(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.backup-schedules.store'), [
                'schedule_name' => 'Daily Full System Backup',
                'backup_type' => 'full',
                'frequency' => 'daily',
                'storage_disk' => 'local',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('backup_schedules', [
            'schedule_name' => 'Daily Full System Backup',
            'frequency' => 'daily',
        ]);
    }

    /**
     * Test console commands.
     */
    public function test_commands_execute_cleanly(): void
    {
        $this->artisan('backup:run database')
            ->expectsOutputToContain('Starting manual system backup')
            ->assertExitCode(0);

        $this->artisan('backup:cleanup')
            ->expectsOutputToContain('Starting historical backup cleanup')
            ->assertExitCode(0);

        $this->artisan('backup:health')
            ->expectsOutputToContain('Running Backup System Health diagnostics')
            ->assertExitCode(0);
    }

    /**
     * Test notification dispatches.
     */
    public function test_backup_sends_notification(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $this->service->createBackup([
            'backup_name' => 'Notification Success Test',
            'backup_type' => BackupType::DATABASE,
            'created_by' => $this->admin->id,
        ]);

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $this->admin,
            \App\Notifications\BackupCompletedNotification::class
        );
    }

    /**
     * Test corrupted checksum blocks restoration.
     */
    public function test_corrupted_checksum_fails_restoration(): void
    {
        $backup = $this->service->createBackup([
            'backup_name' => 'Corrupt Restore Test',
            'backup_type' => BackupType::DATABASE,
            'created_by' => $this->admin->id,
        ]);

        // Purposefully tamper with checksum database record
        $backup->update(['checksum' => 'tampered_sha_256_hash']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Restore aborting: Backup file checksum mismatch.');

        $this->service->restoreBackup($backup, $this->admin->id);
    }
}
