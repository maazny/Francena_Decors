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
        Schema::create('analytics_snapshots', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('snapshot_name', 255);
            $table->string('metric_type', 100);
            $table->string('module', 100);
            $table->string('metric_key', 150);
            $table->decimal('metric_value', 20, 4)->default(0.0000);
            $table->json('metric_data')->nullable();
            $table->timestamp('captured_at');
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Single indexes
            $table->index('metric_type');
            $table->index('metric_key');
            $table->index('captured_at');

            // Composite indexes
            $table->index(['metric_type', 'module'], 'idx_snapshots_type_module');
            $table->index(['module', 'captured_at'], 'idx_snapshots_module_captured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_snapshots');
    }
};
