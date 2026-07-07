<?php

namespace Tests\Feature;

use App\Models\SeoSetting;
use App\Models\SeoPage;
use App\Models\SeoRedirect;
use App\Models\SeoStructuredData;
use App\Models\BlogPost;
use App\Models\User;
use App\Enums\SeoPageType;
use App\Enums\RedirectType;
use App\Enums\StructuredDataType;
use App\Services\SeoRedirectService;
use App\Services\SeoSitemapService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SeoModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user for controller endpoint tests
        $this->admin = User::factory()->create([
            'email' => 'admin_seo@example.com',
        ]);
    }

    /**
     * Test global settings cache works.
     */
    public function test_seo_settings_caching(): void
    {
        Cache::forget('seo_settings');

        $settings = SeoSetting::getCached();
        $this->assertInstanceOf(SeoSetting::class, $settings);
        $this->assertTrue(Cache::has('seo_settings'));

        // Modify database directly
        SeoSetting::first()->update(['site_name' => 'Fancy Override']);

        // Cache should still return original
        $this->assertNotEquals('Fancy Override', SeoSetting::getCached()->site_name);

        // Clear cache and retrieve
        SeoSetting::clearCache();
        $this->assertEquals('Fancy Override', SeoSetting::getCached()->site_name);
    }

    /**
     * Test polymorphic morphTo relation linking to page.
     */
    public function test_seo_page_polymorphic_relation(): void
    {
        $category = \App\Models\BlogCategory::create([
            'name' => 'Interior Trends',
            'slug' => 'interior-trends',
            'status' => true,
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'title' => 'Chic Living Spaces',
            'slug' => 'chic-living-spaces',
            'content' => 'Lorem ipsum...',
            'status' => 'published',
        ]);

        $seoPage = SeoPage::create([
            'page_type' => SeoPageType::MODULE,
            'slug' => '/blog/chic-living-spaces',
            'title' => 'Bespoke Chic Living Spaces | Fancy Decorators',
            'seo_pageable_type' => BlogPost::class,
            'seo_pageable_id' => $post->id,
        ]);

        $this->assertInstanceOf(BlogPost::class, $seoPage->seo_pageable);
        $this->assertEquals('Chic Living Spaces', $seoPage->seo_pageable->title);
    }

    /**
     * Test Structured Data relations.
     */
    public function test_seo_structured_data_relations(): void
    {
        $page = SeoPage::create([
            'page_type' => SeoPageType::STATIC,
            'page_key' => 'home',
            'slug' => '/',
            'title' => 'Fancy Decorators',
        ]);

        $structured = SeoStructuredData::create([
            'seo_page_id' => $page->id,
            'type' => StructuredDataType::FAQ,
            'schema_data' => [
                'question' => 'What is custom construction?',
                'answer' => 'Tailor-made design and build services.',
            ],
            'is_active' => true,
        ]);

        $this->assertCount(1, $page->structuredData);
        $this->assertEquals(StructuredDataType::FAQ, $page->structuredData->first()->type);
        $this->assertEquals('What is custom construction?', $page->structuredData->first()->schema_data['question']);
    }

    /**
     * Test redirects and logs hits relations.
     */
    public function test_seo_redirects_logs_relation(): void
    {
        $redirect = SeoRedirect::create([
            'source_url' => '/old-contact',
            'target_url' => '/contact',
            'type' => RedirectType::PERMANENT,
            'is_active' => true,
        ]);

        $log = \App\Models\SeoLog::create([
            'redirect_id' => $redirect->id,
            'url' => '/old-contact',
            'referrer' => 'google.com',
            'ip_address' => '127.0.0.1',
        ]);

        $this->assertCount(1, $redirect->logs);
        $this->assertEquals('/old-contact', $redirect->logs->first()->url);
    }

    /**
     * Test redirect loop detection.
     */
    public function test_redirect_loop_detection_fails(): void
    {
        $service = new SeoRedirectService();

        // 1. Direct Loop (A -> A)
        $this->assertTrue($service->detectRedirectLoop('/about', '/about'));

        // 2. Chain Loop (A -> B, B -> A)
        SeoRedirect::create([
            'source_url' => '/about-us',
            'target_url' => '/about',
            'type' => RedirectType::PERMANENT,
            'is_active' => true,
        ]);

        $this->assertTrue($service->detectRedirectLoop('/about', '/about-us'));

        // Try to insert redirect that loops
        $this->expectException(\InvalidArgumentException::class);
        $service->createRedirect([
            'source_url' => '/about',
            'target_url' => '/about-us',
            'type' => RedirectType::PERMANENT,
            'is_active' => true,
        ]);
    }

    /**
     * Test wildcard matching.
     */
    public function test_redirect_wildcard_matching(): void
    {
        $service = new SeoRedirectService();

        SeoRedirect::create([
            'source_url' => '/old-blog/*',
            'target_url' => '/blog',
            'type' => RedirectType::PERMANENT,
            'is_wildcard' => true,
            'is_active' => true,
        ]);

        // Clear cache to evaluate
        $service->clearRedirectCache();

        $match = $service->findRedirectMatch('/old-blog/some-post-slug');
        $this->assertNotNull($match);
        $this->assertEquals('/blog', $match->target_url);

        $noMatch = $service->findRedirectMatch('/about-us');
        $this->assertNull($noMatch);
    }

    /**
     * Test dynamic XML sitemap generation.
     */
    public function test_sitemap_xml_generation(): void
    {
        $service = new SeoSitemapService();
        $xml = $service->generateXml();

        $this->assertStringContainsString('<?xml', $xml);
        $this->assertStringContainsString('<urlset', $xml);
        $this->assertStringContainsString('<loc>', $xml);
    }

    /**
     * Test global settings updates via controller.
     */
    public function test_admin_can_update_seo_settings(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.seo.settings.update'), [
                'site_name' => 'Fabulous Decorators',
                'meta_title' => 'Fabulous Construction & Design',
                'meta_description' => 'A luxury construction design company.',
                'theme_color' => '#ffffff',
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('seo_settings', [
            'site_name' => 'Fabulous Decorators',
            'meta_title' => 'Fabulous Construction & Design',
            'theme_color' => '#ffffff',
        ]);
    }

    /**
     * Test admin can access dashboard.
     */
    public function test_admin_can_access_seo_dashboard(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.seo.sitemaps.index')); // Let's check sitemaps index
        $response->assertStatus(200);
    }
}
