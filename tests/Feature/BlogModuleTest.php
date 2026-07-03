<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_routes_are_available(): void
    {
        // Check frontend blog index route
        $response = $this->get(route('blog.index'));
        $response->assertOk();
        $response->assertSee('Our Journal');

        // Check if route helpers are registered correctly
        $this->assertTrue(route('blog.index') !== null);
        $this->assertTrue(route('admin.blog-posts.index') !== null);
        $this->assertTrue(route('admin.blog-categories.index') !== null);
        $this->assertTrue(route('admin.blog-tags.index') !== null);
    }

    public function test_admin_can_store_blog_post_with_galleries(): void
    {
        $admin = User::factory()->create();
        $media = \App\Models\Media::create([
            'title' => 'Test Media',
            'file_name' => 'test-image.jpg',
            'original_name' => 'test-image.jpg',
            'file_path' => 'uploads/test-image.jpg',
            'mime_type' => 'image/jpeg',
            'extension' => 'jpg',
            'file_size' => 1024,
            'is_image' => true,
            'status' => true,
            'uploaded_by' => $admin->id,
        ]);

        $postData = [
            'title' => 'Test Blog Post with Gallery',
            'content' => 'Lorem ipsum content',
            'status' => 1,
            'gallery_media_ids' => [$media->id],
            'gallery_captions' => ['Nice image caption'],
        ];

        $response = $this->actingAs($admin)->post(route('admin.blog-posts.store'), $postData);

        $response->assertRedirect(route('admin.blog-posts.index'));
        $this->assertDatabaseHas('blog_posts', ['title' => 'Test Blog Post with Gallery']);
        $this->assertDatabaseHas('blog_galleries', [
            'media_id' => $media->id,
            'caption' => 'Nice image caption',
        ]);
    }
}
