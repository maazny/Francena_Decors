<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\TestimonialCategory;
use App\Models\User;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\ServiceFaq;
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

        // Seed Service Categories
        $residentialCategory = ServiceCategory::create([
            'name' => 'Residential Construction',
            'slug' => 'residential-construction',
            'short_description' => 'High-end home builds and upscale renovations.',
            'description' => 'We create luxury living spaces tailored to your lifestyle, focusing on superior craftsmanship and custom finishes.',
            'status' => true,
            'display_order' => 1,
        ]);

        $commercialCategory = ServiceCategory::create([
            'name' => 'Commercial Construction',
            'slug' => 'commercial-construction',
            'short_description' => 'Sophisticated retail and office development.',
            'description' => 'Elite commercial building services that translate corporate vision into functional, stunning architectural reality.',
            'status' => true,
            'display_order' => 2,
        ]);

        $interiorCategory = ServiceCategory::create([
            'name' => 'Interior Design',
            'slug' => 'interior-design',
            'short_description' => 'Bespoke interior planning and styling.',
            'description' => 'Sophisticated interior environments curated with premium materials and artisan detail.',
            'status' => true,
            'display_order' => 3,
        ]);

        // Seed Services
        $customBuilds = Service::create([
            'category_id' => $residentialCategory->id,
            'title' => 'Custom Home Builds',
            'slug' => 'custom-home-builds',
            'short_description' => 'Tailored architectural masterpieces built from the ground up.',
            'description' => 'Creating custom luxury estates designed to last generations.',
            'status' => true,
            'is_featured' => true,
            'display_order' => 1,
        ]);

        $renovations = Service::create([
            'category_id' => $residentialCategory->id,
            'title' => 'Home Renovations',
            'slug' => 'home-renovations',
            'short_description' => 'Premium home remodeling and space optimization.',
            'description' => 'Revitalizing kitchens, master suites, and whole properties with modern luxury standards.',
            'status' => true,
            'is_featured' => true,
            'display_order' => 2,
        ]);

        $commercialBuildouts = Service::create([
            'category_id' => $commercialCategory->id,
            'title' => 'Retail & Office Buildouts',
            'slug' => 'retail-office-buildouts',
            'short_description' => 'Premium commercial interior spaces.',
            'description' => 'Delivering corporate environments and boutique shops with high efficiency.',
            'status' => true,
            'is_featured' => true,
            'display_order' => 3,
        ]);

        $bespokeInteriors = Service::create([
            'category_id' => $interiorCategory->id,
            'title' => 'Bespoke Interior Design',
            'slug' => 'bespoke-interior-design',
            'short_description' => 'Artisanal styling and custom millwork curation.',
            'description' => 'Crafting beautiful interiors that express unique personality.',
            'status' => true,
            'is_featured' => true,
            'display_order' => 4,
        ]);

        // Seed Service FAQs
        ServiceFaq::create([
            'service_id' => $customBuilds->id,
            'question' => 'What is the typical timeline for a custom luxury home build?',
            'answer' => 'Typically, a custom luxury home construction takes between 10 to 18 months, depending on the scale and complexity of the architectural design.',
            'status' => true,
            'display_order' => 1,
        ]);

        ServiceFaq::create([
            'service_id' => $customBuilds->id,
            'question' => 'Can I bring my own architect, or do you provide design services?',
            'answer' => 'We can collaborate seamlessly with your chosen architect, or we can offer our full in-house design-build team to guide your project from concept to completion.',
            'status' => true,
            'display_order' => 2,
        ]);

        ServiceFaq::create([
            'service_id' => $renovations->id,
            'question' => 'Do I need to vacate my home during a major renovation?',
            'answer' => 'While not always mandatory, we highly recommend vacating during major structural or whole-home renovations for your safety and comfort, and to accelerate the timeline.',
            'status' => true,
            'display_order' => 3,
        ]);

        ServiceFaq::create([
            'service_id' => $commercialBuildouts->id,
            'question' => 'Do you help with zoning approvals and building permits?',
            'answer' => 'Yes, our team handles the entire permitting process, including coordination with local zoning boards and building authorities.',
            'status' => true,
            'display_order' => 4,
        ]);

        ServiceFaq::create([
            'service_id' => $bespokeInteriors->id,
            'question' => 'How do you source the materials and furnishings for interior projects?',
            'answer' => 'We source high-end, premium materials, custom cabinetry, and luxury furnishings from our global network of elite suppliers and artisans.',
            'status' => true,
            'display_order' => 5,
        ]);

        ServiceFaq::create([
            'service_id' => $bespokeInteriors->id,
            'question' => 'Do you offer physical consultations or virtual design sessions?',
            'answer' => 'We offer comprehensive in-person design sessions at our showroom or your property, as well as high-definition 3D rendering presentations.',
            'status' => true,
            'display_order' => 6,
        ]);
    }
}
