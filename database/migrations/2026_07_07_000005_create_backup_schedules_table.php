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
        Schema::create('backup_schedules', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('schedule_name', 255);
            $table->string('backup_type', 100);

            $table->string('frequency', 50)->index();
            $table->string('cron_expression', 100)->nullable();
            $table->string('storage_disk', 100);
            $table->unsignedInteger('retain_backups')->default(30);
            $table->boolean('is_active')->default(true)->index();

            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();

            // Composite Indexes
            $table->index(['frequency', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_schedules');
    }
};
