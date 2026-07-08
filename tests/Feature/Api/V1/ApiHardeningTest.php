<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ApiHardeningTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    protected $user;

    /**
     * Setup test requirements.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * Test secure headers are present on API responses.
     */
    public function test_api_secure_headers_presence(): void
    {
        $response = $this->getJson('/api/health-check');
        $response->assertStatus(200);

        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('X-API-Version', 'v1');
        $response->assertHeader('X-Response-Time-Ms');
    }

    /**
     * Test Accept header validation rule checks.
     */
    public function test_accept_header_constraint_validations(): void
    {
        $response = $this->get('/api/health-check', [
            'Accept' => 'text/html',
        ]);

        $response->assertStatus(406);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('error', 'Accept header must request application/json.');
    }

    /**
     * Test Content-Type header validations.
     */
    public function test_content_type_header_constraint_validations(): void
    {
        $response = $this->post('/api/v1/login', [], [
            'Accept' => 'application/json',
            'Content-Type' => 'text/plain',
        ]);

        $response->assertStatus(415);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('error', 'Unsupported Media Type. Content-Type must be application/json or multipart/form-data.');
    }
}
