<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_francena_decors_content(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Francena Decors');
        $response->assertSee('Luxury Construction');
    }
}
