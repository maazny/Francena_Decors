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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name', 150)->nullable();
            $table->string('company_name', 150)->nullable();
            $table->string('tagline', 255)->nullable();
            $table->string('company_email', 150)->nullable();
            $table->string('support_email', 150)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('whatsapp', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('google_map', 500)->nullable();
            $table->string('office_hours', 150)->nullable();
            $table->text('copyright')->nullable();
            $table->text('footer_text')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('footer_logo', 255)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('default_language', 20)->nullable();
            $table->string('timezone', 150)->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
