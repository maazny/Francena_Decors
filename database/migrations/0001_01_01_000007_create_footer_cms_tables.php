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
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('layout', 50)->default('four_columns');
            $table->foreignId('logo_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('background_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('company_description')->nullable();
            $table->boolean('show_logo')->default(true);
            $table->boolean('show_description')->default(true);
            $table->boolean('show_columns')->default(true);
            $table->boolean('show_contact')->default(true);
            $table->boolean('show_business_hours')->default(true);
            $table->boolean('show_social_links')->default(true);
            $table->boolean('show_widgets')->default(true);
            $table->boolean('newsletter_enabled')->default(true);
            $table->string('newsletter_title')->nullable();
            $table->text('newsletter_description')->nullable();
            $table->string('newsletter_placeholder')->nullable();
            $table->string('newsletter_button_text')->nullable();
            $table->string('contact_heading')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('business_hours_heading')->nullable();
            $table->text('copyright_text')->nullable();
            $table->boolean('bottom_bar_enabled')->default(true);
            $table->string('bottom_bar_text')->nullable();
            $table->string('background_color', 20)->nullable();
            $table->string('text_color', 20)->nullable();
            $table->string('heading_color', 20)->nullable();
            $table->string('link_color', 20)->nullable();
            $table->string('bottom_background_color', 20)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('footer_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_setting_id')->constrained('footer_settings')->cascadeOnDelete();
            $table->string('title');
            $table->string('type', 50)->default('links');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('footer_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_column_id')->constrained('footer_columns')->cascadeOnDelete();
            $table->string('label');
            $table->string('url', 500)->nullable();
            $table->string('target', 20)->default('_self');
            $table->string('icon', 100)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('footer_social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_setting_id')->constrained('footer_settings')->cascadeOnDelete();
            $table->string('platform', 100);
            $table->string('url', 500);
            $table->string('icon', 100)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('footer_business_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_setting_id')->constrained('footer_settings')->cascadeOnDelete();
            $table->string('day_label', 100);
            $table->string('time_label', 150);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('footer_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_setting_id')->constrained('footer_settings')->cascadeOnDelete();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('icon', 100)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_widgets');
        Schema::dropIfExists('footer_business_hours');
        Schema::dropIfExists('footer_social_links');
        Schema::dropIfExists('footer_links');
        Schema::dropIfExists('footer_columns');
        Schema::dropIfExists('footer_settings');
    }
};
