<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\TestimonialCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@fancydecorators.test',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Seed Testimonial Categories
        $corporateCategory = TestimonialCategory::create([
            'name' => 'Corporate Clients',
            'slug' => 'corporate-clients',
            'description' => 'Testimonials from corporate and enterprise clients',
            'display_order' => 1,
            'status' => true,
        ]);

        $retailCategory = TestimonialCategory::create([
            'name' => 'Retail Partners',
            'slug' => 'retail-partners',
            'description' => 'Testimonials from retail business partners',
            'display_order' => 2,
            'status' => true,
        ]);

        $eventCategory = TestimonialCategory::create([
            'name' => 'Event Clients',
            'slug' => 'event-clients',
            'description' => 'Testimonials from event and special occasion clients',
            'display_order' => 3,
            'status' => true,
        ]);

        // Seed Sample Testimonials
        Testimonial::create([
            'testimonial_category_id' => $corporateCategory->id,
            'client_name' => 'Sarah Johnson',
            'client_company' => 'ABC Corporation',
            'client_designation' => 'Project Manager',
            'rating' => 5,
            'title' => 'Exceptional Quality and Service',
            'testimonial' => 'The team at Fancy Decorators delivered outstanding results for our corporate event. Their attention to detail and professionalism exceeded all expectations. Highly recommended!',
            'location' => 'New York, USA',
            'featured' => true,
            'homepage_featured' => true,
            'display_order' => 1,
            'status' => 'published',
            'approved_at' => now(),
            'seo_title' => 'Sarah Johnson - ABC Corporation Testimonial',
            'seo_description' => 'Read Sarah Johnson\'s testimonial about Fancy Decorators\' exceptional service and quality.',
            'seo_keywords' => 'testimonial, decoration, corporate event, quality service',
        ]);

        Testimonial::create([
            'testimonial_category_id' => $retailCategory->id,
            'client_name' => 'Michael Chen',
            'client_company' => 'XYZ Retail Solutions',
            'client_designation' => 'Store Manager',
            'rating' => 5,
            'title' => 'Transformed Our Store',
            'testimonial' => 'Fancy Decorators completely transformed our retail space. The design suggestions were innovative and helped increase foot traffic significantly. A fantastic investment!',
            'location' => 'Los Angeles, USA',
            'featured' => true,
            'homepage_featured' => true,
            'display_order' => 2,
            'status' => 'published',
            'approved_at' => now(),
            'seo_title' => 'Michael Chen - XYZ Retail Testimonial',
            'seo_description' => 'Learn how Fancy Decorators transformed XYZ Retail Solutions\' store.',
            'seo_keywords' => 'retail decoration, store design, business improvement',
        ]);

        Testimonial::create([
            'testimonial_category_id' => $eventCategory->id,
            'client_name' => 'Emily Williams',
            'client_company' => 'Elite Event Planning',
            'client_designation' => 'Event Director',
            'rating' => 4,
            'title' => 'Creative and Professional',
            'testimonial' => 'Working with Fancy Decorators for our wedding events has been a wonderful experience. Their creative vision and professional execution made our clients extremely happy.',
            'location' => 'Chicago, USA',
            'featured' => false,
            'homepage_featured' => false,
            'display_order' => 3,
            'status' => 'published',
            'approved_at' => now(),
            'seo_title' => 'Emily Williams - Elite Event Planning Testimonial',
            'seo_description' => 'Emily Williams shares her experience with Fancy Decorators for wedding events.',
            'seo_keywords' => 'wedding decoration, event planning, professional service',
        ]);

        Testimonial::create([
            'testimonial_category_id' => $corporateCategory->id,
            'client_name' => 'David Martinez',
            'client_company' => 'Tech Innovations Inc',
            'client_designation' => 'Director of Operations',
            'rating' => 5,
            'title' => 'Perfect Execution Every Time',
            'testimonial' => 'We\'ve worked with Fancy Decorators on multiple projects and they deliver consistent excellence. Their team understands our brand and translates it beautifully into our spaces.',
            'location' => 'San Francisco, USA',
            'featured' => true,
            'homepage_featured' => true,
            'display_order' => 4,
            'status' => 'published',
            'approved_at' => now(),
            'seo_title' => 'David Martinez - Tech Innovations Testimonial',
            'seo_description' => 'David Martinez praises Fancy Decorators for consistent excellence in corporate decoration.',
            'seo_keywords' => 'corporate decoration, brand design, professional execution',
        ]);

        Testimonial::create([
            'testimonial_category_id' => $retailCategory->id,
            'client_name' => 'Jennifer Lee',
            'client_company' => 'Fashion Boutique Ltd',
            'client_designation' => 'Owner',
            'rating' => 5,
            'title' => 'Brought Our Vision to Life',
            'testimonial' => 'Fancy Decorators understood our boutique\'s aesthetic perfectly. They created an elegant shopping environment that reflects our brand values and attracts our target customers.',
            'location' => 'Miami, USA',
            'featured' => false,
            'homepage_featured' => false,
            'display_order' => 5,
            'status' => 'published',
            'approved_at' => now(),
            'seo_title' => 'Jennifer Lee - Fashion Boutique Testimonial',
            'seo_description' => 'Jennifer Lee shares how Fancy Decorators transformed her fashion boutique.',
            'seo_keywords' => 'boutique decoration, retail design, brand aesthetic',
        ]);
    }
}
