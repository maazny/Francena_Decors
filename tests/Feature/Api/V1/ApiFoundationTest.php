<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use App\Models\User;
use App\Helpers\ApiResponseHelper;

class ApiFoundationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('api')->group(function () {
            Route::get('/_test/success', function () {
                return ApiResponseHelper::success(['foo' => 'bar'], 'Success Message', 200, ['page' => 1]);
            });

            Route::get('/_test/model-not-found', function () {
                $exception = new ModelNotFoundException();
                $exception->setModel(User::class);
                throw $exception;
            });

            Route::get('/_test/validation-error', function () {
                $validator = \Illuminate\Support\Facades\Validator::make([], [
                    'email' => 'required',
                ]);
                throw new ValidationException($validator);
            });

            Route::get('/_test/auth-error', function () {
                throw new AuthenticationException('Custom unauthenticated message.');
            });
        });
    }

    /**
     * Test success response format and request ID generation.
     */
    public function test_api_success_response_structure_and_request_id(): void
    {
        $response = $this->getJson('/_test/success');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'foo',
            ],
            'meta' => [
                'page',
            ],
            'errors',
            'timestamp',
            'request_id',
        ]);

        $this->assertTrue($response->json('success'));
        $this->assertEquals('Success Message', $response->json('message'));
        $this->assertEquals('bar', $response->json('data.foo'));
        $this->assertEquals(1, $response->json('meta.page'));
        $this->assertNull($response->json('errors'));
        $this->assertNotNull($response->json('timestamp'));
        $this->assertNotNull($response->json('request_id'));

        $response->assertHeader('X-Request-ID');
        $this->assertEquals($response->json('request_id'), $response->headers->get('X-Request-ID'));
    }

    /**
     * Test exception handling formatting for ModelNotFoundException.
     */
    public function test_api_exception_handling_model_not_found(): void
    {
        $response = $this->getJson('/_test/model-not-found');

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
            'meta',
            'errors',
            'timestamp',
            'request_id',
        ]);

        $this->assertFalse($response->json('success'));
        $this->assertStringContainsString('User not found', $response->json('message'));
        $response->assertHeader('X-Request-ID');
    }

    /**
     * Test exception handling formatting for ValidationException.
     */
    public function test_api_exception_handling_validation_error(): void
    {
        $response = $this->getJson('/_test/validation-error');

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
            'meta',
            'errors' => [
                'email',
            ],
            'timestamp',
            'request_id',
        ]);

        $this->assertFalse($response->json('success'));
        $this->assertEquals('The email field is required.', $response->json('errors.email.0'));
        $response->assertHeader('X-Request-ID');
    }

    /**
     * Test exception handling formatting for AuthenticationException.
     */
    public function test_api_exception_handling_authentication_error(): void
    {
        $response = $this->getJson('/_test/auth-error');

        $response->assertStatus(401);
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
            'meta',
            'errors',
            'timestamp',
            'request_id',
        ]);

        $this->assertFalse($response->json('success'));
        $this->assertEquals('Custom unauthenticated message.', $response->json('message'));
        $response->assertHeader('X-Request-ID');
    }
}
