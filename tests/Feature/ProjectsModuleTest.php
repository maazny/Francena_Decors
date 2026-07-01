<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_routes_and_home_page_are_available(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Featured Projects');
        $this->assertTrue(route('projects.index') !== null);
        $this->assertTrue(route('admin.projects.index') !== null);
    }
}
