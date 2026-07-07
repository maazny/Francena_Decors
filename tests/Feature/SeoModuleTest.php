<?php

namespace Tests\Feature;

use App\Models\SeoSetting;
use App\Models\SeoPage;
use App\Models\SeoRedirect;
use App\Models\SeoStructuredData;
use App\Models\SeoSitemap;
use App\Models\SeoLog;
use App\Models\BlogPost; // Reuse existing model for morph test
use App\Enums\SeoPageType;
use App\Enums\RedirectType;
use App\Enums\StructuredDataType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SeoModuleTest extends TestCase
{
    use RefreshDatabase;

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
        // Create blog post model
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

        // Mapped override
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

        $log = SeoLog::create([
            'redirect_id' => $redirect->id,
            'url' => '/old-contact',
            'referrer' => 'google.com',
            'ip_address' => '127.0.0.1',
        ]);

        $this->assertCount(1, $redirect->logs);
        $this->assertEquals('/old-contact', $redirect->logs->first()->url);
    }
}
