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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Nullable Foreign Keys preserving history on parent deletes
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('module', 100)->index();
            $table->string('action', 100)->index();

            // Polymorphic relations columns
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();

            $table->text('description')->nullable();

            // JSON payloads
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Request/Browser Meta parameters
            $table->ipAddress('ip_address')->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('device', 100)->nullable();
            $table->string('operating_system', 100)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('url')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('session_id', 255)->nullable();
            $table->string('request_id', 255)->nullable();

            $table->string('status', 50)->default('success')->index();

            $table->timestamps();

            // Optimized Database Indexes
            $table->index(['model_type', 'model_id']);
            $table->index(['module', 'action']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
