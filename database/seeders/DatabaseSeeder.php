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

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@francenadecors.test'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        \App\Models\SiteSetting::firstOrCreate([])->update([
            'site_name' => 'Francena Decors',
            'company_name' => 'Francena Decors',
            'tagline' => 'Premium Construction & Interior Solutions Since 2012',
        ]);

        \App\Models\AboutSection::firstOrCreate([])->update([
            'experience_years' => 15,
            'completed_projects' => 500,
            'happy_clients' => 98,
            'team_members' => 120,
            'status' => true,
        ]);

        \App\Models\ThemeSetting::firstOrCreate([])->update([
            'primary_color' => '#004AAD', // Primary Blue
            'secondary_color' => '#003380',
            'accent_color' => '#FF6B07', // Accent Orange
            'background_color' => '#F8FAFC', // Light Gray
            'surface_color' => '#FFFFFF', // White
            'text_color' => '#1E293B',
            'heading_color' => '#0F172A',
            'link_color' => '#004AAD',
            'link_hover_color' => '#003380',
            'button_background' => '#004A99',
            'button_text_color' => '#FFFFFF',
            'button_hover_background' => '#003380',
            'button_hover_text' => '#FFFFFF',
            'navbar_background' => '#0F172A',
            'navbar_text_color' => '#FFFFFF',
            'footer_background' => '#0F172A',
            'footer_text_color' => '#FFFFFF',
            'card_background' => '#FFFFFF',
            'card_border_color' => '#E2E8F0',
            'input_background' => '#FFFFFF',
            'input_border_color' => '#CBD5E1',
            'font_family' => 'Inter',
            'heading_font' => 'Montserrat',
        ]);

        \App\Models\CompanyTimeline::create([
            'year' => '2012',
            'title' => 'Company Founded',
            'description' => 'Established Francena Decors with a vision for premium custom builds.',
            'display_order' => 1,
            'status' => true,
        ]);

        \App\Models\CompanyTimeline::create([
            'year' => '2016',
            'title' => 'Design Studio Launch',
            'description' => 'Opened our signature high-end design showroom in metropolitan area.',
            'display_order' => 2,
            'status' => true,
        ]);

        \App\Models\CompanyTimeline::create([
            'year' => '2020',
            'title' => 'Commercial Division',
            'description' => 'Expanded into master-planned commercial fit-outs and structures.',
            'display_order' => 3,
            'status' => true,
        ]);

        \App\Models\CompanyTimeline::create([
            'year' => '2024',
            'title' => 'Industry Leadership',
            'description' => 'Awarded the custom luxury builder of the year award.',
            'display_order' => 4,
            'status' => true,
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
        $downloadPhoto = function ($url, $name) use ($adminUser) {
            $fileName = 'client_' . uniqid() . '.jpg';
            $filePath = 'media/' . $fileName;
            $imageContent = @file_get_contents($url);
            if ($imageContent !== false) {
                \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $imageContent);
                return \App\Models\Media::create([
                    'title' => $name . ' Profile',
                    'alt_text' => $name,
                    'file_name' => $fileName,
                    'original_name' => $fileName,
                    'file_path' => $filePath,
                    'disk' => 'public',
                    'mime_type' => 'image/jpeg',
                    'extension' => 'jpg',
                    'file_size' => strlen($imageContent),
                    'is_image' => true,
                    'uploaded_by' => $adminUser->id,
                ])->id;
            }
            return null;
        };

        $photo1 = $downloadPhoto('https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80', 'Sarah Johnson');
        Testimonial::create([
            'testimonial_category_id' => $corporateCategory->id,
            'client_photo_id' => $photo1,
            'client_name' => 'Sarah Johnson',
            'client_company' => 'ABC Corporation',
            'client_designation' => 'Project Manager',
            'rating' => 5,
            'title' => 'Exceptional Quality and Service',
            'testimonial' => 'The team at Francena Decors delivered outstanding results for our corporate event. Their attention to detail and professionalism exceeded all expectations. Highly recommended!',
            'location' => 'New York, USA',
            'featured' => true,
            'homepage_featured' => true,
            'display_order' => 1,
            'status' => 'published',
            'approved_at' => now(),
            'seo_title' => 'Sarah Johnson - ABC Corporation Testimonial',
            'seo_description' => 'Read Sarah Johnson\'s testimonial about Francena Decors\' exceptional service and quality.',
            'seo_keywords' => 'testimonial, decoration, corporate event, quality service',
        ]);

        $photo2 = $downloadPhoto('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80', 'Michael Chen');
        Testimonial::create([
            'testimonial_category_id' => $retailCategory->id,
            'client_photo_id' => $photo2,
            'client_name' => 'Michael Chen',
            'client_company' => 'XYZ Retail Solutions',
            'client_designation' => 'Store Manager',
            'rating' => 5,
            'title' => 'Transformed Our Store',
            'testimonial' => 'Francena Decors completely transformed our retail space. The design suggestions were innovative and helped increase foot traffic significantly. A fantastic investment!',
            'location' => 'Los Angeles, USA',
            'featured' => true,
            'homepage_featured' => true,
            'display_order' => 2,
            'status' => 'published',
            'approved_at' => now(),
            'seo_title' => 'Michael Chen - XYZ Retail Testimonial',
            'seo_description' => 'Learn how Francena Decors transformed XYZ Retail Solutions\' store.',
            'seo_keywords' => 'retail decoration, store design, business improvement',
        ]);

        $photo3 = $downloadPhoto('https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=300&q=80', 'Emily Williams');
        Testimonial::create([
            'testimonial_category_id' => $eventCategory->id,
            'client_photo_id' => $photo3,
            'client_name' => 'Emily Williams',
            'client_company' => 'Elite Event Planning',
            'client_designation' => 'Event Director',
            'rating' => 4,
            'title' => 'Creative and Professional',
            'testimonial' => 'Working with Francena Decors for our wedding events has been a wonderful experience. Their creative vision and professional execution made our clients extremely happy.',
            'location' => 'Chicago, USA',
            'featured' => false,
            'homepage_featured' => false,
            'display_order' => 3,
            'status' => 'published',
            'approved_at' => now(),
            'seo_title' => 'Emily Williams - Elite Event Planning Testimonial',
            'seo_description' => 'Emily Williams shares her experience with Francena Decors for wedding events.',
            'seo_keywords' => 'wedding decoration, event planning, professional service',
        ]);

        $photo4 = $downloadPhoto('https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80', 'David Martinez');
        Testimonial::create([
            'testimonial_category_id' => $corporateCategory->id,
            'client_photo_id' => $photo4,
            'client_name' => 'David Martinez',
            'client_company' => 'Tech Innovations Inc',
            'client_designation' => 'Director of Operations',
            'rating' => 5,
            'title' => 'Perfect Execution Every Time',
            'testimonial' => 'We\'ve worked with Francena Decors on multiple projects and they deliver consistent excellence. Their team understands our brand and translates it beautifully into our spaces.',
            'location' => 'San Francisco, USA',
            'featured' => true,
            'homepage_featured' => true,
            'display_order' => 4,
            'status' => 'published',
            'approved_at' => now(),
            'seo_title' => 'David Martinez - Tech Innovations Testimonial',
            'seo_description' => 'David Martinez praises Francena Decors for consistent excellence in corporate decoration.',
            'seo_keywords' => 'corporate decoration, brand design, professional execution',
        ]);

        $photo5 = $downloadPhoto('https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=300&q=80', 'Jennifer Lee');
        Testimonial::create([
            'testimonial_category_id' => $retailCategory->id,
            'client_photo_id' => $photo5,
            'client_name' => 'Jennifer Lee',
            'client_company' => 'Fashion Boutique Ltd',
            'client_designation' => 'Owner',
            'rating' => 5,
            'title' => 'Brought Our Vision to Life',
            'testimonial' => 'Francena Decors understood our boutique\'s aesthetic perfectly. They created an elegant shopping environment that reflects our brand values and attracts our target customers.',
            'location' => 'Miami, USA',
            'featured' => false,
            'homepage_featured' => false,
            'display_order' => 5,
            'status' => 'published',
            'approved_at' => now(),
            'seo_title' => 'Jennifer Lee - Fashion Boutique Testimonial',
            'seo_description' => 'Jennifer Lee shares how Francena Decors transformed her fashion boutique.',
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

        // Seed RBAC permissions and default roles
        $this->call(RbacSeeder::class);

        // Assign super_admin role to default admin user
        $adminUser = User::where('email', 'admin@francenadecors.test')->first();
        if ($adminUser) {
            $superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();
            if ($superAdminRole) {
                $adminUser->roles()->syncWithoutDetaching([$superAdminRole->id]);
            }

            // Seed Hero Sliders
            $slidesData = [
                [
                    'title' => "Building the Future\nWith Quality & Trust",
                    'subtitle' => '',
                    'description' => 'We deliver premium construction, renovation, interior, and architectural solutions with quality craftsmanship and modern design.',
                    'image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80',
                    'video_url' => 'https://cdn.coverr.co/videos/coverr-taking-photos-of-a-house-under-construction-2417/1080p.mp4',
                    'badge_text' => 'WELCOME TO FRANCENA DECORS',
                    'display_order' => 1,
                ],
                [
                    'title' => 'Transforming Existing Spaces',
                    'subtitle' => 'Elite Renovations',
                    'description' => 'High-end architectural remodeling and space optimization tailored to your lifestyle.',
                    'image_url' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1800&q=80',
                    'badge_text' => 'Elite Renovations',
                    'display_order' => 2,
                ],
                [
                    'title' => 'Sophisticated Commercial Builds',
                    'subtitle' => 'Commercial Fit-outs',
                    'description' => 'Translating corporate vision into functional, stunning, and on-time physical realities.',
                    'image_url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1800&q=80',
                    'badge_text' => 'Commercial Fit-outs',
                    'display_order' => 3,
                ],
            ];

            \App\Models\HeroSlider::truncate();

            foreach ($slidesData as $index => $slide) {
                $num = $index + 1;
                $fileName = 'hero_slide_' . $num . '.jpg';
                $filePath = 'media/' . $fileName;

                $imageContent = @file_get_contents($slide['image_url']);
                if ($imageContent !== false) {
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $imageContent);

                    $media = \App\Models\Media::create([
                        'title' => $slide['title'],
                        'alt_text' => $slide['title'],
                        'file_name' => $fileName,
                        'original_name' => $fileName,
                        'file_path' => $filePath,
                        'disk' => 'public',
                        'mime_type' => 'image/jpeg',
                        'extension' => 'jpg',
                        'file_size' => \Illuminate\Support\Facades\Storage::disk('public')->size($filePath),
                        'is_image' => true,
                        'uploaded_by' => $adminUser->id,
                    ]);

                    $videoMediaId = null;
                    if (!empty($slide['video_url'])) {
                        $videoFileName = 'hero_video_' . $num . '.mp4';
                        $videoFilePath = 'media/' . $videoFileName;

                        $opts = [
                            'http' => [
                                'method' => 'GET',
                                'header' => "Referer: https://coverr.co/\r\n"
                            ]
                        ];
                        $context = stream_context_create($opts);
                        $videoContent = @file_get_contents($slide['video_url'], false, $context);

                        if ($videoContent !== false) {
                            \Illuminate\Support\Facades\Storage::disk('public')->put($videoFilePath, $videoContent);

                            $videoMedia = \App\Models\Media::create([
                                'title' => $slide['title'] . ' Video',
                                'alt_text' => $slide['title'] . ' Video',
                                'file_name' => $videoFileName,
                                'original_name' => $videoFileName,
                                'file_path' => $videoFilePath,
                                'disk' => 'public',
                                'mime_type' => 'video/mp4',
                                'extension' => 'mp4',
                                'file_size' => \Illuminate\Support\Facades\Storage::disk('public')->size($videoFilePath),
                                'is_image' => false,
                                'uploaded_by' => $adminUser->id,
                            ]);
                            $videoMediaId = $videoMedia->id;
                        }
                    }

                    \App\Models\HeroSlider::create([
                        'title' => $slide['title'],
                        'subtitle' => $slide['subtitle'],
                        'description' => $slide['description'],
                        'desktop_image_id' => $media->id,
                        'mobile_image_id' => $media->id,
                        'background_video_id' => $videoMediaId,
                        'badge_text' => $slide['badge_text'],
                        'badge_color' => '#d4af5f',
                        'button_one_text' => 'Get Free Quote',
                        'button_one_url' => '#contact',
                        'button_two_text' => 'View Projects',
                        'button_two_url' => '#projects',
                        'text_alignment' => 'center',
                        'content_position' => 'center',
                        'enable_animation' => true,
                        'animation_type' => 'zoom-in',
                        'animation_duration' => 1000,
                        'display_order' => $slide['display_order'],
                        'status' => true,
                    ]);
                }
            }

            \App\Models\HeroSlider::clearCache();

            // Seed project categories
            $resCat = \App\Models\ProjectCategory::create([
                'name' => 'Residential',
                'slug' => 'residential',
                'description' => 'Luxury homes and modern estates.',
                'status' => true,
                'display_order' => 1,
            ]);
            $commCat = \App\Models\ProjectCategory::create([
                'name' => 'Commercial',
                'slug' => 'commercial',
                'description' => 'High-end corporate offices and showrooms.',
                'status' => true,
                'display_order' => 2,
            ]);
            $intCat = \App\Models\ProjectCategory::create([
                'name' => 'Interior',
                'slug' => 'interior',
                'description' => 'Bespoke high-end interior spaces.',
                'status' => true,
                'display_order' => 3,
            ]);
            $renCat = \App\Models\ProjectCategory::create([
                'name' => 'Renovation',
                'slug' => 'renovation',
                'description' => 'Sophisticated restoration and remodeling.',
                'status' => true,
                'display_order' => 4,
            ]);

            // Seed projects data
            $projectsData = [
                [
                    'category_id' => $resCat->id,
                    'title' => 'Modernist Concrete Villa',
                    'location' => 'Beverly Hills, CA',
                    'url' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                ],
                [
                    'category_id' => $resCat->id,
                    'title' => 'Bel Air Glass Manor',
                    'location' => 'Bel Air, CA',
                    'url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
                ],
                [
                    'category_id' => $commCat->id,
                    'title' => 'Nexus Corporate HQ',
                    'location' => 'Seattle, WA',
                    'url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                ],
                [
                    'category_id' => $commCat->id,
                    'title' => 'Aura Retail Gallery',
                    'location' => 'SoHo, NY',
                    'url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1200&q=80',
                ],
                [
                    'category_id' => $intCat->id,
                    'title' => 'Minimalist Penthouse',
                    'location' => 'Manhattan, NY',
                    'url' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1200&q=80',
                ],
                [
                    'category_id' => $renCat->id,
                    'title' => 'Victorian Villa Restoration',
                    'location' => 'San Francisco, CA',
                    'url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
                ],
            ];

            foreach ($projectsData as $idx => $p) {
                $fileName = 'proj_' . uniqid() . '.jpg';
                $filePath = 'media/' . $fileName;
                $imageContent = @file_get_contents($p['url']);
                if ($imageContent !== false) {
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $imageContent);
                    $media = \App\Models\Media::create([
                        'title' => $p['title'],
                        'alt_text' => $p['title'],
                        'file_name' => $fileName,
                        'original_name' => $fileName,
                        'file_path' => $filePath,
                        'disk' => 'public',
                        'mime_type' => 'image/jpeg',
                        'extension' => 'jpg',
                        'file_size' => strlen($imageContent),
                        'is_image' => true,
                        'uploaded_by' => $adminUser->id,
                    ]);

                    \App\Models\Project::create([
                        'project_category_id' => $p['category_id'],
                        'title' => $p['title'],
                        'slug' => strtolower(str_replace(' ', '-', $p['title'])),
                        'short_description' => 'An award-worthy premium project delivered with top-tier materials.',
                        'description' => 'Detailed case study of the premium construction and craftsmanship involved.',
                        'location' => $p['location'],
                        'status' => 'published',
                        'featured' => true,
                        'homepage_featured' => true,
                        'cover_image_id' => $media->id,
                        'display_order' => $idx + 1,
                    ]);
                }
            }
            // Seed Footer Settings dynamically if not exists
            if (\App\Models\FooterSetting::count() === 0) {
                $f = \App\Models\FooterSetting::create([
                    'layout' => 'four_columns',
                    'company_description' => 'Francena Decors: Crafting landmarks of luxury and distinction since 2012.',
                    'show_logo' => false,
                    'show_description' => true,
                    'show_columns' => true,
                    'show_contact' => true,
                    'show_business_hours' => true,
                    'show_social_links' => true,
                    'show_widgets' => false,
                    'newsletter_enabled' => true,
                    'newsletter_title' => 'Newsletter',
                    'newsletter_description' => 'Subscribe to receive updates on premium luxury projects and design trends.',
                    'newsletter_placeholder' => 'Enter your email address',
                    'newsletter_button_text' => 'Subscribe',
                    'contact_heading' => 'Contact Us',
                    'contact_address' => '25 Royal Avenue, Downtown City',
                    'contact_phone' => '+1 234 567 890',
                    'contact_email' => 'hello@francenadecors.com',
                    'business_hours_heading' => 'Working Hours',
                    'copyright_text' => '© 2026 Francena Decors. All Rights Reserved.',
                    'bottom_bar_text' => 'Designed with Excellence.',
                    'bottom_bar_enabled' => true,
                    'status' => true,
                ]);

                $col = $f->columns()->create([
                    'title' => 'Quick Links',
                    'type' => 'links',
                    'sort_order' => 1,
                    'status' => true,
                ]);

                $links = [
                    ['label' => 'Home', 'url' => '/'],
                    ['label' => 'About Us', 'url' => '/#about'],
                    ['label' => 'Services', 'url' => '/services'],
                    ['label' => 'Gallery Portfolio', 'url' => '/gallery'],
                    ['label' => 'Blog Journal', 'url' => '/blog'],
                    ['label' => 'Contact Us', 'url' => '/contact'],
                ];

                foreach ($links as $idx => $link) {
                    $col->links()->create([
                        'label' => $link['label'],
                        'url' => $link['url'],
                        'sort_order' => $idx + 1,
                        'status' => true,
                    ]);
                }

                $socials = [
                    ['platform' => 'Facebook', 'url' => 'https://facebook.com', 'icon' => 'fa-brands fa-facebook-f'],
                    ['platform' => 'Instagram', 'url' => 'https://instagram.com', 'icon' => 'fa-brands fa-instagram'],
                    ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com', 'icon' => 'fa-brands fa-linkedin-in'],
                ];

                foreach ($socials as $idx => $s) {
                    $f->socialLinks()->create([
                        'platform' => $s['platform'],
                        'url' => $s['url'],
                        'icon' => $s['icon'],
                        'sort_order' => $idx + 1,
                        'status' => true,
                    ]);
                }

                $hours = [
                    ['day_label' => 'Mon - Fri', 'time_label' => '9:00 AM - 6:00 PM'],
                    ['day_label' => 'Sat - Sun', 'time_label' => 'Closed'],
                ];

                foreach ($hours as $idx => $h) {
                    $f->businessHours()->create([
                        'day_label' => $h['day_label'],
                        'time_label' => $h['time_label'],
                        'sort_order' => $idx + 1,
                        'status' => true,
                    ]);
                }
            }
        }
    }
}
