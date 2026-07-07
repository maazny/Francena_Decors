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
        Schema::create('backup_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('backup_name', 255);
            $table->string('backup_type', 100)->index();
            $table->text('description')->nullable();

            $table->string('storage_disk', 100)->index();
            $table->text('storage_path');
            $table->string('file_name', 255);
            $table->string('file_extension', 30)->nullable();
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('file_size');
            $table->string('checksum', 255)->nullable();

            $table->boolean('compression')->default(false);
            $table->boolean('encryption')->default(false);
            $table->string('status', 50)->default('pending')->index();

            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('completed_at')->nullable()->index();
            $table->integer('duration_seconds')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->index()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('restored_by')
                ->nullable()
                ->index()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('restore_point', 255)->nullable();
            $table->boolean('is_verified')->default(false)->index();
            $table->text('notes')->nullable();

            $table->timestamp('last_downloaded_at')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->text('failure_reason')->nullable();

            $table->timestamps();

            // Composite Indexes
            $table->index(['status', 'backup_type']);
            $table->index(['created_by', 'created_at']);
            $table->index(['storage_disk', 'backup_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_histories');
    }
};
