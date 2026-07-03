<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Departments
        Schema::create('job_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Categories
        Schema::create('job_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('job_departments')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Locations
        Schema::create('job_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('google_map_embed')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 4. Job Openings
        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('job_departments')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('job_categories')->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('job_locations')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('reference_no')->unique()->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description');
            $table->string('employment_type');
            $table->string('experience_level');
            $table->integer('vacancies')->default(1);
            $table->decimal('salary_from', 12, 2)->nullable();
            $table->decimal('salary_to', 12, 2)->nullable();
            $table->string('salary_type')->nullable();
            $table->dateTime('application_deadline')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('homepage_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->dateTime('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->timestamps();
        });

        // 5. Job Skills
        Schema::create('job_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_opening_id')->constrained('job_openings')->cascadeOnDelete();
            $table->string('skill_name');
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // 6. Job Benefits
        Schema::create('job_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_opening_id')->constrained('job_openings')->cascadeOnDelete();
            $table->string('benefit_name');
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // 7. Job Qualifications
        Schema::create('job_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_opening_id')->constrained('job_openings')->cascadeOnDelete();
            $table->string('qualification_name');
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // 8. Job Applications
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_opening_id')->constrained('job_openings')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->foreignId('resume_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('cover_letter')->nullable();
            $table->decimal('years_of_experience', 4, 1)->default(0.0);
            $table->string('current_company')->nullable();
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->string('application_status')->default('applied');
            $table->text('admin_notes')->nullable();
            $table->dateTime('applied_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_qualifications');
        Schema::dropIfExists('job_benefits');
        Schema::dropIfExists('job_skills');
        Schema::dropIfExists('job_openings');
        Schema::dropIfExists('job_locations');
        Schema::dropIfExists('job_categories');
        Schema::dropIfExists('job_departments');
    }
};
