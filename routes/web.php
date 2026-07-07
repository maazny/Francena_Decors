<?php

use App\Http\Controllers\Admin\AboutSectionController;
use App\Http\Controllers\Admin\ClientBrandController;
use App\Http\Controllers\Admin\CompanyTimelineController;
use App\Http\Controllers\Admin\CompanyValueController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FooterSettingController;
use App\Http\Controllers\Admin\HeaderLogoController;
use App\Http\Controllers\Admin\HeaderSettingController;
use App\Http\Controllers\Admin\HeaderTopbarController;
use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceFaqController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialCategoryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ThemeSettingController;
use App\Http\Controllers\Admin\WhyChooseUsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ClientsBrandsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ServicePageController;
use App\Http\Controllers\TestimonialsController;
use Illuminate\Support\Facades\Route;

Route::put('/admin/theme-settings/update', [ThemeSettingController::class, 'update'])
    ->name('theme.settings.update');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Frontend Team pages
use App\Http\Controllers\TeamController;

Route::get('/team', [TeamController::class, 'index'])->name('team.index');
Route::get('/team/department/{team_department:slug}', [TeamController::class, 'department'])->name('team.department');
Route::get('/team/{team_member:slug}', [TeamController::class, 'show'])->name('team.show');

Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectsController::class, 'show'])->name('projects.show');
Route::get('/projects/category/{projectCategory:slug}', [ProjectsController::class, 'category'])->name('projects.category');

Route::get('/testimonials', [TestimonialsController::class, 'index'])->name('testimonials.index');
Route::get('/testimonials/{testimonial}', [TestimonialsController::class, 'show'])->name('testimonials.show');
Route::get('/testimonials/category/{testimonialCategory:slug}', [TestimonialsController::class, 'byCategory'])->name('testimonials.category');

Route::get('/clients-brands', [ClientsBrandsController::class, 'index'])->name('clients-brands.index');
Route::get('/clients-brands/{clientBrand:slug}', [ClientsBrandsController::class, 'show'])->name('clients-brands.show');

Route::get('/services', [ServicePageController::class, 'index'])->name('services.index');
Route::get('/services/category/{slug}', [ServicePageController::class, 'category'])->name('services.category');
Route::get('/services/{slug}', [ServicePageController::class, 'show'])->name('services.show');

// Frontend Blog pages
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog_post:slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Frontend Careers pages
Route::get('/careers', [\App\Http\Controllers\CareersController::class, 'index'])->name('careers.index');
Route::get('/careers/jobs', [\App\Http\Controllers\CareersController::class, 'jobs'])->name('careers.jobs');
Route::get('/careers/jobs/{slug}', [\App\Http\Controllers\CareersController::class, 'show'])->name('careers.show');
Route::post('/careers/jobs/{slug}/apply', [\App\Http\Controllers\CareersController::class, 'apply'])->name('careers.apply');

// Frontend Contact CMS (Module 23)
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Frontend Newsletter CMS
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/verify/{token}', [\App\Http\Controllers\NewsletterController::class, 'verify'])->name('newsletter.verify');
Route::get('/newsletter/unsubscribe/{token}', [\App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::post('/newsletter/unsubscribe/{token}', [\App\Http\Controllers\NewsletterController::class, 'postUnsubscribe'])->name('newsletter.post-unsubscribe');
Route::get('/newsletter/preferences/{token}', [\App\Http\Controllers\NewsletterController::class, 'preferences'])->name('newsletter.preferences')->middleware('signed');
Route::post('/newsletter/preferences/{token}', [\App\Http\Controllers\NewsletterController::class, 'updatePreferences'])->name('newsletter.update-preferences')->middleware('signed');

// XML Sitemap Route
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route(auth()->check() ? 'admin.dashboard' : 'admin.login');
    })->name('index');

    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.submit');

        Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

        Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('media', [MediaController::class, 'index'])->name('media.index');
        Route::get('media/create', [MediaController::class, 'create'])->name('media.create');
        Route::post('media', [MediaController::class, 'store'])->name('media.store');
        Route::get('media/{media}/download', [MediaController::class, 'download'])->name('media.download');
        Route::get('media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
        Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
        Route::post('media/bulk-delete', [MediaController::class, 'bulkDelete'])->name('media.bulk-delete');
        Route::get('media/{media}', [MediaController::class, 'show'])->name('media.show');

        Route::name('theme.settings.')->group(function () {
            Route::get('theme-settings', [ThemeSettingController::class, 'edit'])->name('edit');
            Route::put('theme-settings', [ThemeSettingController::class, 'update'])->name('update');
        });

        Route::name('header.settings.')->group(function () {
            Route::get('header-settings', [HeaderSettingController::class, 'edit'])->name('edit');
            Route::put('header-settings', [HeaderSettingController::class, 'update'])->name('update');
        });

        Route::name('header.topbars.')->group(function () {
            Route::get('header-topbars', [HeaderTopbarController::class, 'edit'])->name('edit');
            Route::put('header-topbars', [HeaderTopbarController::class, 'update'])->name('update');
        });

        Route::name('header.logos.')->group(function () {
            Route::get('header-logos', [HeaderLogoController::class, 'edit'])->name('edit');
            Route::put('header-logos', [HeaderLogoController::class, 'update'])->name('update');
        });

        Route::name('footer.settings.')->group(function () {
            Route::get('footer-settings', [FooterSettingController::class, 'edit'])->name('edit');
            Route::put('footer-settings', [FooterSettingController::class, 'update'])->name('update');
        });

        Route::post('hero-sliders/bulk', [HeroSliderController::class, 'bulk'])->name('hero-sliders.bulk');
        Route::post('hero-sliders/reorder', [HeroSliderController::class, 'reorder'])->name('hero-sliders.reorder');
        Route::post('hero-sliders/{heroSlider}/toggle-status', [HeroSliderController::class, 'toggleStatus'])->name('hero-sliders.toggle-status');
        Route::post('hero-sliders/{heroSlider}/duplicate', [HeroSliderController::class, 'duplicate'])->name('hero-sliders.duplicate');
        Route::post('hero-sliders/{heroSlider}/restore', [HeroSliderController::class, 'restore'])->name('hero-sliders.restore');
        Route::resource('hero-sliders', HeroSliderController::class);

        Route::get('about-sections', [AboutSectionController::class, 'edit'])->name('about-sections.edit');
        Route::put('about-sections', [AboutSectionController::class, 'update'])->name('about-sections.update');
        Route::resource('company-values', CompanyValueController::class)->except(['index', 'show']);
        Route::resource('company-timelines', CompanyTimelineController::class)->except(['index', 'show']);
        Route::resource('why-choose-us', WhyChooseUsController::class)->except(['index', 'show']);

        Route::get('site-settings', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
        Route::put('site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');

        Route::post('service-categories/bulk', [ServiceCategoryController::class, 'bulk'])->name('service-categories.bulk');
        Route::post('service-categories/{serviceCategory}/toggle-status', [ServiceCategoryController::class, 'toggleStatus'])->name('service-categories.toggle-status');
        Route::post('service-categories/{serviceCategory}/restore', [ServiceCategoryController::class, 'restore'])->name('service-categories.restore');
        Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);

        // Blog CMS - Admin (Module 21)
        Route::post('blog-categories/bulk', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'bulk'])->name('blog-categories.bulk');
        Route::post('blog-categories/{blog_category}/toggle-status', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'toggleStatus'])->name('blog-categories.toggle-status');
        Route::post('blog-categories/{blog_category}/restore', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'restore'])->name('blog-categories.restore');
        Route::resource('blog-categories', \App\Http\Controllers\Admin\BlogCategoryController::class)->except(['show']);

        Route::post('blog-tags/bulk', [\App\Http\Controllers\Admin\BlogTagController::class, 'bulk'])->name('blog-tags.bulk');
        Route::post('blog-tags/{blog_tag}/toggle-status', [\App\Http\Controllers\Admin\BlogTagController::class, 'toggleStatus'])->name('blog-tags.toggle-status');
        Route::post('blog-tags/{blog_tag}/restore', [\App\Http\Controllers\Admin\BlogTagController::class, 'restore'])->name('blog-tags.restore');
        Route::resource('blog-tags', \App\Http\Controllers\Admin\BlogTagController::class)->except(['show']);

        Route::post('blog-posts/bulk', [\App\Http\Controllers\Admin\BlogPostController::class, 'bulk'])->name('blog-posts.bulk');
        Route::post('blog-posts/{blog_post}/toggle-status', [\App\Http\Controllers\Admin\BlogPostController::class, 'toggleStatus'])->name('blog-posts.toggle-status');
        Route::post('blog-posts/{blog_post}/restore', [\App\Http\Controllers\Admin\BlogPostController::class, 'restore'])->name('blog-posts.restore');
        Route::post('blog-posts/{blog_post}/duplicate', [\App\Http\Controllers\Admin\BlogPostController::class, 'duplicate'])->name('blog-posts.duplicate');
        Route::get('blog-posts/{blog_post}/preview', [\App\Http\Controllers\Admin\BlogPostController::class, 'preview'])->name('blog-posts.preview');
        Route::resource('blog-posts', \App\Http\Controllers\Admin\BlogPostController::class)->except(['show']);

        Route::post('services/bulk', [ServiceController::class, 'bulk'])->name('services.bulk');
        Route::post('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        Route::post('services/{service}/restore', [ServiceController::class, 'restore'])->name('services.restore');
        Route::post('services/{service}/duplicate', [ServiceController::class, 'duplicate'])->name('services.duplicate');
        Route::resource('services', ServiceController::class)->except(['show']);

        Route::post('services/{service}/faqs', [ServiceFaqController::class, 'store'])->name('services.faqs.store');
        Route::put('service-faqs/{serviceFaq}', [ServiceFaqController::class, 'update'])->name('service-faqs.update');
        Route::delete('service-faqs/{serviceFaq}', [ServiceFaqController::class, 'destroy'])->name('service-faqs.destroy');
        Route::post('service-faqs/{serviceFaq}/toggle-status', [ServiceFaqController::class, 'toggleStatus'])->name('service-faqs.toggle-status');

        Route::post('project-categories/bulk', [ProjectCategoryController::class, 'bulk'])->name('project-categories.bulk');
        Route::resource('project-categories', ProjectCategoryController::class)->except(['show']);

        Route::post('projects/bulk', [ProjectController::class, 'bulk'])->name('projects.bulk');
        Route::post('projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
        Route::post('projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
        Route::post('projects/{project}/duplicate', [ProjectController::class, 'duplicate'])->name('projects.duplicate');
        Route::resource('projects', ProjectController::class)->except(['show']);

        Route::post('testimonial-categories/bulk', [TestimonialCategoryController::class, 'bulk'])->name('testimonial-categories.bulk');
        Route::post('testimonial-categories/{testimonialCategory}/toggle-status', [TestimonialCategoryController::class, 'toggleStatus'])->name('testimonial-categories.toggle-status');
        Route::post('testimonial-categories/{testimonialCategory}/restore', [TestimonialCategoryController::class, 'restore'])->name('testimonial-categories.restore');
        Route::resource('testimonial-categories', TestimonialCategoryController::class)->except(['show']);

        Route::post('testimonials/bulk', [TestimonialController::class, 'bulk'])->name('testimonials.bulk');
        Route::post('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
        Route::post('testimonials/{testimonial}/restore', [TestimonialController::class, 'restore'])->name('testimonials.restore');
        Route::post('testimonials/{testimonial}/duplicate', [TestimonialController::class, 'duplicate'])->name('testimonials.duplicate');
        Route::resource('testimonials', TestimonialController::class)->except(['show']);

        Route::post('client-brands/bulk', [ClientBrandController::class, 'bulk'])->name('client-brands.bulk');
        Route::post('client-brands/{clientBrand}/toggle-status', [ClientBrandController::class, 'toggleStatus'])->name('client-brands.toggle-status');
        Route::post('client-brands/{clientBrand}/restore', [ClientBrandController::class, 'restore'])->name('client-brands.restore');
        Route::post('client-brands/{clientBrand}/duplicate', [ClientBrandController::class, 'duplicate'])->name('client-brands.duplicate');
        Route::resource('client-brands', ClientBrandController::class)->except(['show']);

        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        // Team CMS - Admin
        Route::post('team-departments/reorder', [\App\Http\Controllers\Admin\TeamDepartmentController::class, 'reorder'])->name('team-departments.reorder');
        Route::post('team-departments/bulk', [\App\Http\Controllers\Admin\TeamDepartmentController::class, 'bulk'])->name('team-departments.bulk');
        Route::post('team-departments/{team_department}/restore', [\App\Http\Controllers\Admin\TeamDepartmentController::class, 'restore'])->name('team-departments.restore');
        Route::post('team-departments/{team_department}/toggle-status', [\App\Http\Controllers\Admin\TeamDepartmentController::class, 'toggleStatus'])->name('team-departments.toggle-status');
        Route::resource('team-departments', \App\Http\Controllers\Admin\TeamDepartmentController::class)->except(['show']);

        Route::post('team-members/reorder', [\App\Http\Controllers\Admin\TeamMemberController::class, 'reorder'])->name('team-members.reorder');
        Route::post('team-members/bulk', [\App\Http\Controllers\Admin\TeamMemberController::class, 'bulk'])->name('team-members.bulk');
        Route::post('team-members/{team_member}/restore', [\App\Http\Controllers\Admin\TeamMemberController::class, 'restore'])->name('team-members.restore');
        Route::post('team-members/{team_member}/toggle-status', [\App\Http\Controllers\Admin\TeamMemberController::class, 'toggleStatus'])->name('team-members.toggle-status');
        Route::post('team-members/{team_member}/duplicate', [\App\Http\Controllers\Admin\TeamMemberController::class, 'duplicate'])->name('team-members.duplicate');
        Route::resource('team-members', \App\Http\Controllers\Admin\TeamMemberController::class)->except(['show']);

        // Nested resource actions for social links, skills and certifications
        Route::post('team-members/{team_member}/social-links', [\App\Http\Controllers\Admin\TeamSocialLinkController::class, 'store'])->name('team-members.social-links.store');
        Route::put('team-social-links/{team_social_link}', [\App\Http\Controllers\Admin\TeamSocialLinkController::class, 'update'])->name('team-social-links.update');
        Route::delete('team-social-links/{team_social_link}', [\App\Http\Controllers\Admin\TeamSocialLinkController::class, 'destroy'])->name('team-social-links.destroy');
        Route::post('team-social-links/reorder', [\App\Http\Controllers\Admin\TeamSocialLinkController::class, 'reorder'])->name('team-social-links.reorder');

        Route::post('team-members/{team_member}/skills', [\App\Http\Controllers\Admin\TeamSkillController::class, 'store'])->name('team-members.skills.store');
        Route::put('team-skills/{team_skill}', [\App\Http\Controllers\Admin\TeamSkillController::class, 'update'])->name('team-skills.update');
        Route::delete('team-skills/{team_skill}', [\App\Http\Controllers\Admin\TeamSkillController::class, 'destroy'])->name('team-skills.destroy');
        Route::post('team-skills/reorder', [\App\Http\Controllers\Admin\TeamSkillController::class, 'reorder'])->name('team-skills.reorder');

        Route::post('team-members/{team_member}/certifications', [\App\Http\Controllers\Admin\TeamCertificationController::class, 'store'])->name('team-members.certifications.store');
        Route::put('team-certifications/{team_certification}', [\App\Http\Controllers\Admin\TeamCertificationController::class, 'update'])->name('team-certifications.update');
        Route::delete('team-certifications/{team_certification}', [\App\Http\Controllers\Admin\TeamCertificationController::class, 'destroy'])->name('team-certifications.destroy');
        Route::post('team-certifications/reorder', [\App\Http\Controllers\Admin\TeamCertificationController::class, 'reorder'])->name('team-certifications.reorder');

        // Careers CMS - Admin
        Route::prefix('careers')->name('careers.')->group(function () {
            // Job Departments
            Route::post('departments/bulk-delete', [\App\Http\Controllers\Admin\JobDepartmentController::class, 'bulkDelete'])->name('departments.bulk-delete');
            Route::post('departments/bulk-status', [\App\Http\Controllers\Admin\JobDepartmentController::class, 'bulkStatus'])->name('departments.bulk-status');
            Route::post('departments/{job_department}/restore', [\App\Http\Controllers\Admin\JobDepartmentController::class, 'restore'])->name('departments.restore');
            Route::post('departments/{job_department}/toggle-status', [\App\Http\Controllers\Admin\JobDepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');
            Route::resource('departments', \App\Http\Controllers\Admin\JobDepartmentController::class)->except(['show']);

            // Job Categories
            Route::post('categories/bulk-delete', [\App\Http\Controllers\Admin\JobCategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
            Route::post('categories/bulk-status', [\App\Http\Controllers\Admin\JobCategoryController::class, 'bulkStatus'])->name('categories.bulk-status');
            Route::post('categories/{job_category}/restore', [\App\Http\Controllers\Admin\JobCategoryController::class, 'restore'])->name('categories.restore');
            Route::post('categories/{job_category}/toggle-status', [\App\Http\Controllers\Admin\JobCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
            Route::resource('categories', \App\Http\Controllers\Admin\JobCategoryController::class)->except(['show']);

            // Job Locations
            Route::post('locations/bulk-delete', [\App\Http\Controllers\Admin\JobLocationController::class, 'bulkDelete'])->name('locations.bulk-delete');
            Route::post('locations/bulk-status', [\App\Http\Controllers\Admin\JobLocationController::class, 'bulkStatus'])->name('locations.bulk-status');
            Route::post('locations/{job_location}/restore', [\App\Http\Controllers\Admin\JobLocationController::class, 'restore'])->name('locations.restore');
            Route::post('locations/{job_location}/toggle-status', [\App\Http\Controllers\Admin\JobLocationController::class, 'toggleStatus'])->name('locations.toggle-status');
            Route::resource('locations', \App\Http\Controllers\Admin\JobLocationController::class)->except(['show']);

            // Job Openings
            Route::post('jobs/bulk-delete', [\App\Http\Controllers\Admin\JobOpeningController::class, 'bulkDelete'])->name('jobs.bulk-delete');
            Route::post('jobs/bulk-status', [\App\Http\Controllers\Admin\JobOpeningController::class, 'bulkStatus'])->name('jobs.bulk-status');
            Route::post('jobs/{job}/restore', [\App\Http\Controllers\Admin\JobOpeningController::class, 'restore'])->name('jobs.restore');
            Route::post('jobs/{job}/toggle-status', [\App\Http\Controllers\Admin\JobOpeningController::class, 'toggleStatus'])->name('jobs.toggle-status');
            Route::post('jobs/{job}/toggle-featured', [\App\Http\Controllers\Admin\JobOpeningController::class, 'toggleFeatured'])->name('jobs.toggle-featured');
            Route::post('jobs/{job}/toggle-homepage-featured', [\App\Http\Controllers\Admin\JobOpeningController::class, 'toggleHomepageFeatured'])->name('jobs.toggle-homepage-featured');
            Route::post('jobs/{job}/duplicate', [\App\Http\Controllers\Admin\JobOpeningController::class, 'duplicate'])->name('jobs.duplicate');
            Route::resource('jobs', \App\Http\Controllers\Admin\JobOpeningController::class);

            // Job Applications
            Route::post('applications/bulk-delete', [\App\Http\Controllers\Admin\JobApplicationController::class, 'bulkDelete'])->name('applications.bulk-delete');
            Route::post('applications/bulk-status', [\App\Http\Controllers\Admin\JobApplicationController::class, 'bulkStatus'])->name('applications.bulk-status');
            Route::post('applications/{job_application}/restore', [\App\Http\Controllers\Admin\JobApplicationController::class, 'restore'])->name('applications.restore');
            Route::post('applications/{job_application}/toggle-status', [\App\Http\Controllers\Admin\JobApplicationController::class, 'toggleStatus'])->name('applications.toggle-status');
            Route::resource('applications', \App\Http\Controllers\Admin\JobApplicationController::class)->except(['edit']);
        });

        // Contact CMS - Admin (Module 23)
        Route::prefix('contacts')->name('contacts.')->group(function () {
            // Categories
            Route::post('categories/bulk-delete', [\App\Http\Controllers\Admin\ContactCategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
            Route::post('categories/bulk-status', [\App\Http\Controllers\Admin\ContactCategoryController::class, 'bulkStatus'])->name('categories.bulk-status');
            Route::post('categories/{category}/restore', [\App\Http\Controllers\Admin\ContactCategoryController::class, 'restore'])->name('categories.restore');
            Route::post('categories/{category}/toggle-status', [\App\Http\Controllers\Admin\ContactCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
            Route::resource('categories', \App\Http\Controllers\Admin\ContactCategoryController::class)->except(['show']);

            // Inquiries (Contacts)
            Route::post('inquiries/bulk-delete', [\App\Http\Controllers\Admin\ContactController::class, 'bulkDelete'])->name('inquiries.bulk-delete');
            Route::post('inquiries/bulk-status', [\App\Http\Controllers\Admin\ContactController::class, 'bulkStatus'])->name('inquiries.bulk-status');
            Route::post('inquiries/bulk-assign', [\App\Http\Controllers\Admin\ContactController::class, 'bulkAssign'])->name('inquiries.bulk-assign');
            Route::post('inquiries/{contact}/restore', [\App\Http\Controllers\Admin\ContactController::class, 'restore'])->name('inquiries.restore');
            Route::post('inquiries/{contact}/assign', [\App\Http\Controllers\Admin\ContactController::class, 'assign'])->name('inquiries.assign');
            Route::post('inquiries/{contact}/status', [\App\Http\Controllers\Admin\ContactController::class, 'updateStatus'])->name('inquiries.status');
            Route::post('inquiries/{contact}/follow-up', [\App\Http\Controllers\Admin\ContactController::class, 'setFollowUp'])->name('inquiries.follow-up');
            Route::post('inquiries/{contact}/toggle-read', [\App\Http\Controllers\Admin\ContactController::class, 'toggleRead'])->name('inquiries.toggle-read');
            
            // Nested replies & notes
            Route::post('inquiries/{contact}/reply', [\App\Http\Controllers\Admin\ContactReplyController::class, 'store'])->name('inquiries.reply');
            Route::post('inquiries/{contact}/note', [\App\Http\Controllers\Admin\ContactNoteController::class, 'store'])->name('inquiries.note');

            Route::resource('inquiries', \App\Http\Controllers\Admin\ContactController::class);
        });

        // Newsletter CMS - Admin
        Route::prefix('newsletter')->name('newsletter.')->group(function () {
            // Subscribers
            Route::post('subscribers/bulk-delete', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'bulkDelete'])->name('subscribers.bulk-delete');
            Route::post('subscribers/bulk-status', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'bulkStatus'])->name('subscribers.bulk-status');
            Route::post('subscribers/{subscriber}/toggle-status', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'toggleStatus'])->name('subscribers.toggle-status');
            Route::resource('subscribers', \App\Http\Controllers\Admin\NewsletterSubscriberController::class);

            // Groups
            Route::post('groups/bulk-delete', [\App\Http\Controllers\Admin\NewsletterGroupController::class, 'bulkDelete'])->name('groups.bulk-delete');
            Route::post('groups/{group}/toggle-status', [\App\Http\Controllers\Admin\NewsletterGroupController::class, 'toggleStatus'])->name('groups.toggle-status');
            Route::resource('groups', \App\Http\Controllers\Admin\NewsletterGroupController::class);

            // Templates
            Route::post('templates/bulk-delete', [\App\Http\Controllers\Admin\NewsletterCampaignTemplateController::class, 'bulkDelete'])->name('templates.bulk-delete');
            Route::resource('templates', \App\Http\Controllers\Admin\NewsletterCampaignTemplateController::class);

            // Campaigns
            Route::post('campaigns/bulk-delete', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'bulkDelete'])->name('campaigns.bulk-delete');
            Route::post('campaigns/{campaign}/send', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'send'])->name('campaigns.send');
            Route::get('campaigns/{campaign}/preview', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'preview'])->name('campaigns.preview');
            Route::get('campaigns/{campaign}/logs', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'logs'])->name('campaigns.logs');
            Route::resource('campaigns', \App\Http\Controllers\Admin\NewsletterCampaignController::class);
        });
    });
});
