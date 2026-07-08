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
        Schema::create('analytics_reports', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('report_name', 255);
            $table->string('report_type', 100);
            $table->string('module', 100);
            $table->string('period', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('status', 50)->default('pending');
            $table->unsignedInteger('total_records')->default(0);
            $table->longText('report_data')->nullable();
            $table->json('filters')->nullable();
            $table->text('file_path')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->timestamp('generated_at')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamp('last_downloaded_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Single indexes
            $table->index('report_type');
            $table->index('module');
            $table->index('status');
            $table->index('period');
            $table->index('generated_at');
            $table->index('created_at');

            // Composite indexes
            $table->index(['module', 'report_type'], 'idx_reports_module_type');
            $table->index(['module', 'period'], 'idx_reports_module_period');
            $table->index(['status', 'generated_at'], 'idx_reports_status_gen_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_reports');
    }
};
