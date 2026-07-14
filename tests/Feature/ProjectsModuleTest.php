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

    public function test_admin_can_toggle_project_category_status(): void
    {
        $user = \App\Models\User::factory()->create();
        $category = \App\Models\ProjectCategory::create([
            'name' => 'Architecture Design',
            'slug' => 'architecture-design',
            'display_order' => 1,
            'status' => true,
        ]);

        $response = $this->actingAs($user)->post(route('admin.project-categories.toggle-status', $category));

        $response->assertRedirect(route('admin.project-categories.index'));
        $this->assertFalse($category->fresh()->status);
    }

    public function test_admin_can_restore_project_category(): void
    {
        $user = \App\Models\User::factory()->create();
        $category = \App\Models\ProjectCategory::create([
            'name' => 'Landscape Design',
            'slug' => 'landscape-design',
            'display_order' => 2,
            'status' => true,
        ]);

        $category->delete();
        $this->assertSoftDeleted('project_categories', ['id' => $category->id]);

        $response = $this->actingAs($user)->post(route('admin.project-categories.restore', $category->id));

        $response->assertRedirect(route('admin.project-categories.index'));
        $this->assertNotSoftDeleted('project_categories', ['id' => $category->id]);
    }
}
