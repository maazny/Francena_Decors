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
        Schema::create('hero_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('desktop_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('mobile_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('background_video_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('overlay_color', 20)->nullable();
            $table->unsignedTinyInteger('overlay_opacity')->default(65);
            $table->string('text_alignment', 20)->default('center');
            $table->string('content_position', 30)->default('center');
            $table->string('button_one_text')->nullable();
            $table->string('button_one_url', 500)->nullable();
            $table->string('button_one_target', 20)->default('_self');
            $table->string('button_two_text')->nullable();
            $table->string('button_two_url', 500)->nullable();
            $table->string('button_two_target', 20)->default('_self');
            $table->string('badge_text')->nullable();
            $table->string('badge_color', 20)->nullable();
            $table->boolean('enable_animation')->default(true);
            $table->string('animation_type', 50)->default('fade-up');
            $table->unsignedSmallInteger('animation_duration')->default(900);
            $table->unsignedInteger('display_order')->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'start_date', 'end_date']);
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_sliders');
    }
};
