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
        Schema::create('about_sections', function (Blueprint $table) {
            $table->id();
            $table->text('company_story')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->text('chairman_message')->nullable();
            $table->string('chairman_name')->nullable();
            $table->string('chairman_designation')->nullable();
            $table->foreignId('chairman_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('company_video_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('brochure_file_id')->nullable()->constrained('media')->nullOnDelete();
            $table->unsignedInteger('experience_years')->default(0);
            $table->unsignedInteger('completed_projects')->default(0);
            $table->unsignedInteger('happy_clients')->default(0);
            $table->unsignedInteger('team_members')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->foreignId('og_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('company_values', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'display_order']);
        });

        Schema::create('company_timelines', function (Blueprint $table) {
            $table->id();
            $table->string('year', 20);
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'display_order']);
        });

        Schema::create('why_choose_us', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('why_choose_us');
        Schema::dropIfExists('company_timelines');
        Schema::dropIfExists('company_values');
        Schema::dropIfExists('about_sections');
    }
};
