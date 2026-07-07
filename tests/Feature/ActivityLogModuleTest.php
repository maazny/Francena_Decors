<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use App\Contracts\ActivityLogServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ActivityLogModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected ActivityLogServiceInterface $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        
        // Define admin default permissions to bypass gates or test permissions
        $superAdminRole = Role::create([
            'name' => 'super_admin',
            'label' => 'Super Administrator',
            'is_system' => true,
        ]);
        $this->admin->roles()->attach($superAdminRole->id);

        $this->service = app(ActivityLogServiceInterface::class);
    }

    /**
     * Test service can record activity successfully.
     */
    public function test_service_can_record_activity(): void
    {
        $log = $this->service->log([
            'user_id' => $this->admin->id,
            'module' => 'blog',
            'action' => ActivityAction::CREATE,
            'description' => 'Created a new blog post titled Hello World',
            'status' => ActivityStatus::SUCCESS,
        ]);

        $this->assertNotNull($log);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin->id,
            'module' => 'blog',
            'action' => 'create',
            'status' => 'success',
        ]);
    }

    /**
     * Test search and filter parameters.
     */
    public function test_searching_and_filtering_logs(): void
    {
        $this->service->log([
            'user_id' => $this->admin->id,
            'module' => 'careers',
            'action' => ActivityAction::APPROVE,
            'description' => 'Approved candidate application',
            'status' => ActivityStatus::SUCCESS,
        ]);

        $this->service->log([
            'user_id' => $this->admin->id,
            'module' => 'seo',
            'action' => ActivityAction::SEO_UPDATE,
            'description' => 'Updated meta description tags',
            'status' => ActivityStatus::FAILED,
        ]);

        // Test search
        $searchResults = $this->service->searchLogs('candidate');
        $this->assertCount(1, $searchResults->items());
        $this->assertEquals('careers', $searchResults->items()[0]->module);

        // Test filtering
        $filteredResults = $this->service->getLogs(['module' => 'seo']);
        $this->assertCount(1, $filteredResults->items());
        $this->assertEquals('failed', $filteredResults->items()[0]->status->value);
    }

    /**
     * Test statistics calculations.
     */
    public function test_statistics_aggregation(): void
    {
        ActivityLog::query()->delete();
        Cache::forget('activity_logs:statistics');

        $this->service->log([
            'user_id' => $this->admin->id,
            'module' => 'blog',
            'action' => ActivityAction::CREATE,
            'status' => ActivityStatus::SUCCESS,
        ]);

        $stats = $this->service->getDashboardStatistics();

        $this->assertEquals(1, $stats['total_logs']);
        $this->assertEquals(1, $stats['successful_logs']);
        $this->assertEquals(0, $stats['failed_logs']);
    }

    /**
     * Test queue logging works when enabled in config.
     */
    public function test_queue_logging_dispatches_job(): void
    {
        Queue::fake();

        config(['activitylog.queue_logging' => true]);

        $this->service->log([
            'user_id' => $this->admin->id,
            'module' => 'contacts',
            'action' => ActivityAction::CONTACT_REPLY,
            'status' => ActivityStatus::SUCCESS,
        ]);

        Queue::assertPushed(\App\Jobs\LogActivityJob::class);
    }

    /**
     * Test admin routes permissions and access control.
     */
    public function test_admin_routes_accessibility(): void
    {
        // Add log
        $this->service->log([
            'user_id' => $this->admin->id,
            'module' => 'blog',
            'action' => ActivityAction::CREATE,
            'status' => ActivityStatus::SUCCESS,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.activity-logs.index'));
        $response->assertOk();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.activity-logs.statistics'));
        $response->assertOk();
    }

    /**
     * Test dynamic model observer audits Eloquent event hooks automatically.
     */
    public function test_observer_audits_model_events(): void
    {
        $department = \App\Models\JobDepartment::create([
            'name' => 'Engineering',
            'slug' => 'engineering',
            'description' => 'Engineering department',
            'status' => true,
        ]);

        // Trigger model creation on Careers job category
        $category = \App\Models\JobCategory::create([
            'department_id' => $department->id,
            'name' => 'Architecture and Construction',
            'slug' => 'architecture-construction',
            'description' => 'Construction management positions.',
            'status' => true,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'model_type' => \App\Models\JobCategory::class,
            'model_id' => $category->id,
            'action' => 'create',
            'module' => 'job_category',
        ]);

        // Trigger model update
        $category->update(['name' => 'Luxury Architecture']);

        $this->assertDatabaseHas('activity_logs', [
            'model_type' => \App\Models\JobCategory::class,
            'model_id' => $category->id,
            'action' => 'update',
            'module' => 'job_category',
        ]);
    }

    /**
     * Test authentication listener records login actions automatically.
     */
    public function test_auth_subscriber_logs_authentication(): void
    {
        // Fake authentication login event
        event(new \Illuminate\Auth\Events\Login('web', $this->admin, false));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin->id,
            'module' => 'authentication',
            'action' => 'login',
            'status' => 'success',
        ]);
    }

    /**
     * Test cleanup Artisan command prunes older records correctly.
     */
    public function test_cleanup_artisan_command(): void
    {
        // Seed logs with a custom past created_at date
        $oldLog = ActivityLog::create([
            'user_id' => $this->admin->id,
            'module' => 'blog',
            'action' => ActivityAction::CREATE,
            'status' => ActivityStatus::SUCCESS,
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
        ]);
        // Modify date in database manually
        \Illuminate\Support\Facades\DB::table('activity_logs')
            ->where('id', $oldLog->id)
            ->update(['created_at' => now()->subDays(100)]);

        config(['activitylog.retention_days' => 90]);

        $this->artisan('activitylog:cleanup')
            ->expectsOutput("Pruning activity logs older than 90 days...")
            ->expectsOutput("Pruned 1 log entries successfully.")
            ->assertExitCode(0);

        $this->assertDatabaseMissing('activity_logs', ['id' => $oldLog->id]);
    }
}
