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
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('primary_color', 20)->nullable();
            $table->string('secondary_color', 20)->nullable();
            $table->string('accent_color', 20)->nullable();
            $table->string('background_color', 20)->nullable();
            $table->string('surface_color', 20)->nullable();
            $table->string('text_color', 20)->nullable();
            $table->string('heading_color', 20)->nullable();
            $table->string('link_color', 20)->nullable();
            $table->string('link_hover_color', 20)->nullable();
            $table->string('button_background', 20)->nullable();
            $table->string('button_text_color', 20)->nullable();
            $table->string('button_hover_background', 20)->nullable();
            $table->string('button_hover_text', 20)->nullable();
            $table->string('navbar_background', 20)->nullable();
            $table->string('navbar_text_color', 20)->nullable();
            $table->string('footer_background', 20)->nullable();
            $table->string('footer_text_color', 20)->nullable();
            $table->string('sidebar_background', 20)->nullable();
            $table->string('sidebar_text_color', 20)->nullable();
            $table->string('card_background', 20)->nullable();
            $table->string('card_border_color', 20)->nullable();
            $table->string('input_background', 20)->nullable();
            $table->string('input_border_color', 20)->nullable();
            $table->string('success_color', 20)->nullable();
            $table->string('warning_color', 20)->nullable();
            $table->string('danger_color', 20)->nullable();
            $table->string('info_color', 20)->nullable();
            $table->string('font_family', 100)->nullable();
            $table->string('heading_font', 100)->nullable();
            $table->unsignedSmallInteger('base_font_size')->default(16);
            $table->string('border_radius', 50)->nullable();
            $table->string('box_shadow', 255)->nullable();
            $table->unsignedSmallInteger('container_width')->default(1200);
            $table->boolean('loader_enabled')->default(false);
            $table->string('loader_style', 100)->nullable();
            $table->string('loader_color', 20)->nullable();
            $table->string('loader_background', 20)->nullable();
            $table->boolean('dark_mode')->default(false);
            $table->boolean('animation_enabled')->default(true);
            $table->unsignedSmallInteger('animation_speed')->default(300);
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
