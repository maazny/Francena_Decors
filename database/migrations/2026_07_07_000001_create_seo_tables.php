<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. seo_settings (Global Defaults)
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('robots')->default('index, follow');
            $table->string('canonical_url')->nullable();
            
            // Open Graph default structures
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            
            // Twitter Card defaults
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            
            // Icons & Branding
            $table->string('favicon')->nullable();
            $table->string('apple_touch_icon')->nullable();
            $table->string('manifest_file')->nullable();
            $table->string('theme_color', 7)->default('#d4af5f');
            
            // Robots.txt database-driven text configuration
            $table->text('robots_txt_rules')->nullable();
            
            $table->timestamps();
        });

        // 2. seo_pages (Page specific overrides & Morphic configurations)
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_type')->default('static'); // Enum SeoPageType: static, dynamic, module
            $table->string('page_key')->unique()->nullable(); // Unique key for fast lookup (e.g. 'home', 'about')
            $table->string('slug')->nullable(); // Route slug path representation
            
            // Overrides
            $table->string('title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots')->default('index, follow');
            
            // Open Graph Overrides
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('twitter_card')->default('summary_large_image');
            
            // Custom script injection blocks (Safe from HTML escape filters)
            $table->text('custom_head_scripts')->nullable();
            $table->text('custom_footer_scripts')->nullable();

            // Polymorphic link for any module page model
            $table->nullableMorphs('seo_pageable');

            $table->timestamps();

            // Indexes
            $table->index('slug');
            $table->index('page_type');
        });

        // 3. seo_redirects (URL re-routing Rules)
        Schema::create('seo_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('source_url')->unique(); // Requested path url (e.g. '/about-us')
            $table->string('target_url'); // Re-route target path or external link
            $table->integer('type')->default(301); // 301 Permanent, 302 Temporary
            $table->boolean('is_wildcard')->default(false); // E.g. match /old-blog/*
            $table->boolean('is_active')->default(true);
            $table->integer('hit_count')->default(0); // Cumulative redirected count
            $table->timestamps();

            // Indexes
            $table->index('is_active');
        });

        // 4. seo_structured_data (Schema JSON-LD tags mapped to pages)
        Schema::create('seo_structured_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seo_page_id')->nullable()->constrained('seo_pages')->onDelete('cascade');
            $table->string('type'); // Enum StructuredDataType: organization, website, faq, etc.
            $table->json('schema_data')->nullable(); // Structured key-value schema parameters
            $table->text('custom_json_ld')->nullable(); // Raw user-supplied JSON-LD script block
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('type');
        });

        // 5. seo_sitemaps (Dynamic sitemap registrations)
        Schema::create('seo_sitemaps', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('xml'); // xml, image, blog, custom sitemap file types
            $table->string('loc')->unique(); // Sitemap URI path location (e.g. 'sitemap-blog.xml')
            $table->timestamp('lastmod')->nullable();
            $table->string('changefreq')->default('weekly');
            $table->decimal('priority', 2, 1)->default(0.5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->index('is_active');
        });

        // 6. seo_logs (Analytic redirection bot crawler audits)
        Schema::create('seo_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('redirect_id')->nullable()->constrained('seo_redirects')->onDelete('set null');
            $table->string('url')->nullable();
            $table->string('referrer')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->boolean('crawler_detected')->default(false);
            $table->timestamps();

            $table->index('crawler_detected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_logs');
        Schema::dropIfExists('seo_sitemaps');
        Schema::dropIfExists('seo_structured_data');
        Schema::dropIfExists('seo_redirects');
        Schema::dropIfExists('seo_pages');
        Schema::dropIfExists('seo_settings');
    }
};
