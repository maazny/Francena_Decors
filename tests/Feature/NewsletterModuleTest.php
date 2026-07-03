<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\NewsletterGroup;
use App\Models\NewsletterCampaignTemplate;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterCampaignLog;
use App\Enums\SubscriptionStatus;
use App\Enums\SubscriberSource;
use App\Enums\CampaignStatus;
use App\Enums\CampaignType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test newsletter models and database relationships.
     */
    public function test_newsletter_models_relationships(): void
    {
        // 1. Create Subscriber
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'status' => SubscriptionStatus::ACTIVE,
            'source' => SubscriberSource::WEBSITE,
        ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'status' => 'active',
            'source' => 'website',
        ]);
        $this->assertEquals(SubscriptionStatus::ACTIVE, $subscriber->status);
        $this->assertEquals(SubscriberSource::WEBSITE, $subscriber->source);

        // 2. Create Group
        $group = NewsletterGroup::create([
            'name' => 'VIP Customers',
            'slug' => 'vip-customers',
            'status' => true,
        ]);

        $this->assertDatabaseHas('newsletter_groups', [
            'name' => 'VIP Customers',
            'slug' => 'vip-customers',
            'status' => 1,
        ]);

        // 3. Associate Subscriber and Group
        $subscriber->groups()->attach($group->id);

        $this->assertCount(1, $subscriber->groups);
        $this->assertEquals('VIP Customers', $subscriber->groups->first()->name);

        $this->assertCount(1, $group->subscribers);
        $this->assertEquals('Jane Smith', $group->subscribers->first()->name);

        // 4. Create Template
        $template = NewsletterCampaignTemplate::create([
            'name' => 'Standard Newsletter Layout',
            'subject' => 'Welcome to Fancy Decorators',
            'html_content' => '<h1>Hello</h1>',
        ]);

        $this->assertDatabaseHas('newsletter_campaign_templates', [
            'name' => 'Standard Newsletter Layout',
        ]);

        // 5. Create Campaign
        $campaign = NewsletterCampaign::create([
            'title' => 'July Newsletter',
            'slug' => 'july-newsletter',
            'subject' => 'Decorations Trends for July 2026',
            'campaign_type' => CampaignType::NEWSLETTER,
            'template_id' => $template->id,
            'html_content' => '<h1>Decorations Trends for July 2026</h1>',
            'status' => CampaignStatus::DRAFT,
            'sender_name' => 'Fancy Admin',
            'sender_email' => 'admin@fancydecorators.test',
        ]);

        $this->assertDatabaseHas('newsletter_campaigns', [
            'title' => 'July Newsletter',
            'slug' => 'july-newsletter',
            'status' => 'draft',
        ]);
        $this->assertEquals(CampaignType::NEWSLETTER, $campaign->campaign_type);
        $this->assertEquals(CampaignStatus::DRAFT, $campaign->status);
        $this->assertEquals($template->id, $campaign->template->id);

        // 6. Create Log
        $log = NewsletterCampaignLog::create([
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'delivery_status' => 'delivered',
            'opened' => true,
        ]);

        $this->assertDatabaseHas('newsletter_campaign_logs', [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'opened' => 1,
        ]);
        $this->assertEquals($campaign->id, $log->campaign->id);
        $this->assertEquals($subscriber->id, $log->subscriber->id);
    }
}
