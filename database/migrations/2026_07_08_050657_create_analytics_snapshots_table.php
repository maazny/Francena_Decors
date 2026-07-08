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
            $table->date('snapshot_date')->index();
            $table->string('metric_group')->index();
            $table->string('metric_name')->index();
            $table->decimal('metric_value', 15, 2)->default(0.00);
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Compound index for fast lookup of metrics on a specific date range
            $table->index(['snapshot_date', 'metric_group', 'metric_name'], 'idx_date_group_name');
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
