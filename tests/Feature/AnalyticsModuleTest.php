<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Contracts\SnapshotServiceInterface;
use Illuminate\Support\Facades\Artisan;

/**
 * Class AnalyticsModuleTest
 * @package Tests\Feature
 */
class AnalyticsModuleTest extends TestCase
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

        $this->adminUser = User::factory()->create();
        $role = Role::create(['name' => 'admin_role', 'label' => 'Admin Role', 'is_system' => true]);
        $this->adminUser->roles()->attach($role);
        
        \Illuminate\Support\Facades\Gate::define('analytics.view', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('analytics.dashboard', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('analytics.reports', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('analytics.health', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('analytics.snapshots', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('analytics.compare', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('analytics.export', fn($user) => true);
    }

    /**
     * Test system snapshots can be captured and queried.
     */
    public function test_snapshots_can_be_captured_and_queried(): void
    {
        $service = app(SnapshotServiceInterface::class);
        $service->captureSystemSnapshots();

        $this->assertDatabaseHas('analytics_snapshots', [
            'metric_key' => 'database_size_bytes'
        ]);

        $this->actingAs($this->adminUser);
        $response = $this->get('/admin/analytics/snapshots');
        $response->assertStatus(200);
    }

    /**
     * Test reports can be generated successfully.
     */
    public function test_reports_can_be_generated_successfully(): void
    {
        $this->actingAs($this->adminUser);

        $response = $this->post('/admin/reports/generate', [
            'report_name' => 'Monthly SEO Audit',
            'report_type' => 'seo',
            'period' => 'monthly',
            'start_date' => now()->subMonth()->toDateString(),
            'end_date' => now()->toDateString(),
        ]);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('analytics_reports', [
            'report_name' => 'Monthly SEO Audit',
            'status' => 'completed'
        ]);
    }

    /**
     * Test artisan command executions.
     */
    public function test_artisan_commands_execute(): void
    {
        $exit = Artisan::call('analytics:snapshot');
        $this->assertEquals(0, $exit);

        $exitCleanup = Artisan::call('analytics:cleanup');
        $this->assertEquals(0, $exitCleanup);
    }
}
