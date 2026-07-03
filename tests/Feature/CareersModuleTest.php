<?php

namespace Tests\Feature;

use App\Models\JobDepartment;
use App\Models\JobCategory;
use App\Models\JobLocation;
use App\Models\JobOpening;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CareersModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test careers index and listings pages.
     */
    public function test_careers_portal_pages_are_accessible(): void
    {
        // Careers index page
        $response = $this->get(route('careers.index'));
        $response->assertOk();
        $response->assertSee('Build Your Legacy');

        // Job listings page
        $response = $this->get(route('careers.jobs'));
        $response->assertOk();
        $response->assertSee('Open Positions');
    }

    /**
     * Test online job application submission.
     */
    public function test_candidate_can_submit_application(): void
    {
        // Create a User to satisfy media upload foreign key constraint
        User::factory()->create(['id' => 1]);

        // Seed test department, category, location, and job opening
        $dept = JobDepartment::create(['name' => 'Engineering', 'slug' => 'engineering', 'status' => true]);
        $cat = JobCategory::create(['department_id' => $dept->id, 'name' => 'Software Engineer', 'slug' => 'software-engineer', 'status' => true]);
        $loc = JobLocation::create(['name' => 'Headquarters', 'slug' => 'hq', 'status' => true]);
        
        $job = JobOpening::create([
            'department_id' => $dept->id,
            'category_id' => $cat->id,
            'location_id' => $loc->id,
            'title' => 'Laravel Developer',
            'slug' => 'laravel-developer',
            'description' => '<p>Develop beautiful code</p>',
            'employment_type' => 'Full-time',
            'experience_level' => 'Senior',
            'vacancies' => 2,
            'status' => true,
        ]);

        // Mock upload resume file
        $resume = UploadedFile::fake()->create('resume.pdf', 500, 'application/pdf');

        $appData = [
            'full_name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'cover_letter' => 'I would love to work with you guys.',
            'resume' => $resume,
            'terms' => 'on',
        ];

        // Submit application
        $response = $this->post(route('careers.apply', $job->slug), $appData);

        $response->assertRedirect(route('careers.show', $job->slug));
        $this->assertDatabaseHas('job_applications', [
            'job_opening_id' => $job->id,
            'full_name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
    }
}
