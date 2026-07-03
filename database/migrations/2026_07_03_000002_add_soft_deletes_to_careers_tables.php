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
        Schema::table('job_departments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('job_categories', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('job_locations', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('job_openings', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('job_openings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('job_locations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('job_categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('job_departments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
