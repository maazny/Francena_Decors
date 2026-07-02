<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 191);
            $table->string('slug', 191)->unique();
            $table->string('excerpt')->nullable();
            $table->text('content')->nullable();
            $table->unsignedBigInteger('featured_image_id')->nullable();
            $table->unsignedBigInteger('banner_image_id')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('featured_image_id')->references('id')->on('media')->nullOnDelete();
            $table->foreign('banner_image_id')->references('id')->on('media')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
