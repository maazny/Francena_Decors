<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('slug', 191)->unique();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('banner_image_id')->nullable();
            $table->unsignedBigInteger('featured_image_id')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('banner_image_id')->references('id')->on('media')->nullOnDelete();
            $table->foreign('featured_image_id')->references('id')->on('media')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
