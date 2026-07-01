<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('team_departments')->nullOnDelete();
            $table->string('full_name');
            $table->string('slug')->unique();
            $table->string('designation')->nullable();
            $table->text('short_bio')->nullable();
            $table->longText('full_bio')->nullable();
            $table->foreignId('profile_photo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('cover_photo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('featured')->default(false);
            $table->boolean('homepage_featured')->default(false);
            $table->date('joining_date')->nullable();
            $table->boolean('status')->default(true);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
