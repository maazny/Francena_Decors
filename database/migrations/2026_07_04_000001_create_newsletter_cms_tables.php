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
        // 1. Subscribers
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->boolean('verification_status')->default(false);
            $table->string('status')->default('pending');
            $table->string('source')->default('website');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('preferred_language')->default('en');
            $table->json('tags')->nullable();
            $table->string('verification_token')->nullable()->index();
            $table->string('unsubscribe_token')->nullable()->index();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['status', 'verification_status']);
            $table->index('source');
        });

        // 2. Groups
        Schema::create('newsletter_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_dynamic')->default(false);
            $table->json('filters')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('display_order');
        });

        // 3. Group Subscriber Pivot
        Schema::create('newsletter_group_subscriber', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('newsletter_groups')->cascadeOnDelete();
            $table->foreignId('subscriber_id')->constrained('newsletter_subscribers')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['group_id', 'subscriber_id']);
        });

        // 4. Campaign Templates
        Schema::create('newsletter_campaign_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject')->nullable();
            $table->longText('html_content');
            $table->longText('plain_content')->nullable();
            $table->timestamps();
        });

        // 5. Campaigns
        Schema::create('newsletter_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subject');
            $table->string('preview_text')->nullable();
            $table->string('campaign_type')->default('newsletter');
            $table->foreignId('template_id')->nullable()->constrained('newsletter_campaign_templates')->nullOnDelete();
            $table->longText('html_content');
            $table->longText('plain_text')->nullable();
            $table->timestamp('scheduled_at')->nullable()->index();
            $table->timestamp('sent_at')->nullable();
            $table->string('status')->default('draft');
            $table->string('sender_name');
            $table->string('sender_email');
            $table->string('analytics_utm_source')->nullable();
            $table->string('analytics_utm_medium')->nullable();
            $table->string('analytics_utm_campaign')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('status');
        });

        // 6. Campaign Logs
        Schema::create('newsletter_campaign_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('newsletter_campaigns')->cascadeOnDelete();
            $table->foreignId('subscriber_id')->constrained('newsletter_subscribers')->cascadeOnDelete();
            $table->string('delivery_status')->default('pending');
            $table->text('error_message')->nullable();
            $table->boolean('opened')->default(false)->index();
            $table->boolean('clicked')->default(false)->index();
            $table->boolean('bounced')->default(false)->index();
            $table->boolean('failed')->default(false)->index();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamps();

            // Composite / individual indices
            $table->index('delivery_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaign_logs');
        Schema::dropIfExists('newsletter_campaigns');
        Schema::dropIfExists('newsletter_campaign_templates');
        Schema::dropIfExists('newsletter_group_subscriber');
        Schema::dropIfExists('newsletter_groups');
        Schema::dropIfExists('newsletter_subscribers');
    }
};
