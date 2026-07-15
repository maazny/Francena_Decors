<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\ThemeSetting;
use App\Models\FooterSetting;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class ApiAuthAndContentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    protected $user;

    /**
     * Set up tests configurations.
     */
    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::create([
            'site_name' => 'Francena Decors',
            'company_name' => 'Francena Decors LLC',
            'company_email' => 'info@francenadecors.com',
            'tagline' => 'Decorate your dreams',
            'status' => true,
        ]);

        ThemeSetting::create([
            'primary_color' => '#123456',
            'secondary_color' => '#654321',
            'font_family' => 'Outfit',
        ]);

        FooterSetting::create([
            'copyright_text' => 'Copyright 2026',
        ]);

        Role::create(['name' => 'super_admin', 'label' => 'Super Admin', 'is_system' => true]);
        $userRole = Role::create(['name' => 'editor', 'label' => 'Editor', 'is_system' => false]);

        $this->user = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->user->roles()->sync([$userRole->id]);
    }

    /**
     * Test public home page and settings.
     */
    public function test_public_home_and_settings_endpoints(): void
    {
        $response = $this->getJson('/api/v1/home');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.settings.site.site_name', 'Francena Decors');

        $response = $this->getJson('/api/v1/settings');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.site.company_name', 'Francena Decors LLC');
    }

    /**
     * Test Sanctum authentication flow.
     */
    public function test_api_login_validation_and_token_issuance(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'jane.doe@example.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(422);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'jane.doe@example.com',
            'password' => 'password123',
            'device_name' => 'Test Phone',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure(['data' => ['token', 'user']]);
        $this->assertNotEmpty($response->json('data.token'));
    }

    /**
     * Test profile retrieval and modification when authenticated.
     */
    public function test_profile_retrieval_and_update(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/v1/profile');
        $response->assertStatus(200);
        $response->assertJsonPath('data.email', 'jane.doe@example.com');

        $response = $this->putJson('/api/v1/profile', [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('Jane Smith', $this->user->fresh()->name);
    }

    /**
     * Test password change API.
     */
    public function test_password_change_flow(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/change-password', [
            'current_password' => 'password123',
            'password' => 'newsecurepassword',
            'password_confirmation' => 'newsecurepassword',
        ]);

        $response->assertStatus(200);
        $this->assertTrue(Hash::check('newsecurepassword', $this->user->fresh()->password));
    }

    /**
     * Test token revocation and logout.
     */
    public function test_logout_and_session_invalidation(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/logout');
        $response->assertStatus(200);
    }
}
