<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testimonial_category_id')->nullable()->constrained('testimonial_categories')->nullOnDelete();
            $table->string('client_name');
            $table->string('client_company')->nullable();
            $table->string('client_designation')->nullable();
            $table->foreignId('client_photo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('client_logo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->unsignedTinyInteger('rating')->default(5)->comment('1-5 star rating');
            $table->string('title')->nullable();
            $table->longText('testimonial');
            $table->string('video_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('location')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('homepage_featured')->default(false);
            $table->integer('display_order')->default(0);
            $table->string('status')->default('draft')->comment('draft, published, archived');
            $table->timestamp('approved_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'featured']);
            $table->index(['status', 'homepage_featured']);
            $table->index(['testimonial_category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
