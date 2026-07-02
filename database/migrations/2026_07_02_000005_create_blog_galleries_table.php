<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('blog_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained('blog_posts')->cascadeOnDelete();
            $table->unsignedBigInteger('media_id')->nullable();
            $table->string('caption')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('media_id')->references('id')->on('media')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_galleries');
    }
};
