<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_non_existent_page_returns_custom_404_view(): void
    {
        $response = $this->get('/this-path-does-not-exist-at-all');

        $response->assertStatus(404)
            ->assertSee('Page Not Found')
            ->assertSee('Error 404');
    }
}
