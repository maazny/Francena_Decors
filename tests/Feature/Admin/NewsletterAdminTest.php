<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\NewsletterSubscriber;
use App\Models\NewsletterGroup;
use App\Models\NewsletterCampaignTemplate;
use App\Models\NewsletterCampaign;
use App\Enums\SubscriptionStatus;
use App\Enums\CampaignStatus;
use App\Enums\CampaignType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NewsletterAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    /**
     * Test admin can access all newsletter CMS pages.
     */
    public function test_admin_can_access_newsletter_pages(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.newsletter.subscribers.index'));
        $response->assertOk()->assertSee('Subscriber List');

        $response = $this->actingAs($this->admin)->get(route('admin.newsletter.groups.index'));
        $response->assertOk()->assertSee('Group List');

        $response = $this->actingAs($this->admin)->get(route('admin.newsletter.templates.index'));
        $response->assertOk()->assertSee('Template List');

        $response = $this->actingAs($this->admin)->get(route('admin.newsletter.campaigns.index'));
        $response->assertOk()->assertSee('Campaign List');
    }

    /**
     * Test admin CRUD operations on subscribers.
     */
    public function test_admin_can_manage_subscribers(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.newsletter.subscribers.store'), [
            'name' => 'Alice Admin',
            'email' => 'alice.admin@example.com',
            'preferred_language' => 'en',
        ]);
        $response->assertRedirect(route('admin.newsletter.subscribers.index'));
        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'alice.admin@example.com']);

        $subscriber = NewsletterSubscriber::where('email', 'alice.admin@example.com')->first();

        // 2. Edit/Update
        $response = $this->actingAs($this->admin)->put(route('admin.newsletter.subscribers.update', $subscriber->id), [
            'name' => 'Alice Updated',
            'email' => 'alice.admin@example.com',
            'status' => 'unsubscribed',
        ]);
        $response->assertRedirect(route('admin.newsletter.subscribers.index'));
        $this->assertDatabaseHas('newsletter_subscribers', [
            'id' => $subscriber->id,
            'name' => 'Alice Updated',
            'status' => 'unsubscribed'
        ]);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('admin.newsletter.subscribers.destroy', $subscriber->id));
        $response->assertRedirect(route('admin.newsletter.subscribers.index'));
        $this->assertSoftDeleted('newsletter_subscribers', ['id' => $subscriber->id]);
    }

    /**
     * Test admin CRUD operations on groups.
     */
    public function test_admin_can_manage_groups(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.newsletter.groups.store'), [
            'name' => 'Test Group',
            'slug' => 'test-group',
            'description' => 'A test cohort',
        ]);
        $response->assertRedirect(route('admin.newsletter.groups.index'));
        $this->assertDatabaseHas('newsletter_groups', ['name' => 'Test Group']);

        $group = NewsletterGroup::where('slug', 'test-group')->first();

        // 2. Update
        $response = $this->actingAs($this->admin)->put(route('admin.newsletter.groups.update', $group->id), [
            'name' => 'Test Group Modified',
            'slug' => 'test-group',
            'description' => 'Modified description',
        ]);
        $response->assertRedirect(route('admin.newsletter.groups.index'));
        $this->assertDatabaseHas('newsletter_groups', [
            'id' => $group->id,
            'name' => 'Test Group Modified'
        ]);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('admin.newsletter.groups.destroy', $group->id));
        $response->assertRedirect(route('admin.newsletter.groups.index'));
        $this->assertDatabaseMissing('newsletter_groups', ['id' => $group->id]);
    }

    /**
     * Test admin CRUD operations on campaign templates.
     */
    public function test_admin_can_manage_templates(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.newsletter.templates.store'), [
            'name' => 'Template X',
            'subject' => 'Subject X',
            'html_content' => '<h1>Hello @{{name}}</h1>',
        ]);
        $response->assertRedirect(route('admin.newsletter.templates.index'));
        $this->assertDatabaseHas('newsletter_campaign_templates', ['name' => 'Template X']);

        $template = NewsletterCampaignTemplate::where('name', 'Template X')->first();

        // 2. Update
        $response = $this->actingAs($this->admin)->put(route('admin.newsletter.templates.update', $template->id), [
            'name' => 'Template X Modified',
            'subject' => 'Subject X Modified',
            'html_content' => '<h1>Hello Modified @{{name}}</h1>',
        ]);
        $response->assertRedirect(route('admin.newsletter.templates.index'));
        $this->assertDatabaseHas('newsletter_campaign_templates', [
            'id' => $template->id,
            'name' => 'Template X Modified'
        ]);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('admin.newsletter.templates.destroy', $template->id));
        $response->assertRedirect(route('admin.newsletter.templates.index'));
        $this->assertDatabaseMissing('newsletter_campaign_templates', ['id' => $template->id]);
    }

    /**
     * Test admin CRUD operations on campaigns.
     */
    public function test_admin_can_manage_campaigns(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.newsletter.campaigns.store'), [
            'title' => 'Campaign X',
            'subject' => 'Subject X',
            'campaign_type' => 'newsletter',
            'html_content' => '<p>Check out our products.</p>',
            'sender_name' => 'Fancy Store',
            'sender_email' => 'store@example.com',
        ]);
        $response->assertRedirect(route('admin.newsletter.campaigns.index'));
        $this->assertDatabaseHas('newsletter_campaigns', ['title' => 'Campaign X']);

        $campaign = NewsletterCampaign::where('title', 'Campaign X')->first();

        // 2. Update
        $response = $this->actingAs($this->admin)->put(route('admin.newsletter.campaigns.update', $campaign->id), [
            'title' => 'Campaign X Modified',
            'subject' => 'Subject X Modified',
            'campaign_type' => 'newsletter',
            'html_content' => '<p>Updated content.</p>',
            'sender_name' => 'Fancy Store',
            'sender_email' => 'store@example.com',
        ]);
        $response->assertRedirect(route('admin.newsletter.campaigns.index'));
        $this->assertDatabaseHas('newsletter_campaigns', [
            'id' => $campaign->id,
            'title' => 'Campaign X Modified'
        ]);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('admin.newsletter.campaigns.destroy', $campaign->id));
        $response->assertRedirect(route('admin.newsletter.campaigns.index'));
        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
    }

    /**
     * Test triggering manual campaign send dispatches queue job.
     */
    public function test_admin_can_trigger_campaign_dispatch(): void
    {
        Queue::fake();

        $campaign = NewsletterCampaign::create([
            'title' => 'Send Test Campaign',
            'slug' => 'send-test-campaign',
            'subject' => 'Testing Dispatch',
            'campaign_type' => CampaignType::NEWSLETTER,
            'html_content' => '<p>Hello world</p>',
            'status' => CampaignStatus::DRAFT,
            'sender_name' => 'Tester',
            'sender_email' => 'tester@example.com',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.newsletter.campaigns.send', $campaign->id));

        $response->assertRedirect(route('admin.newsletter.campaigns.index'))
            ->assertSessionHas('success');

        // Assert campaign status changed to sending
        $this->assertEquals(CampaignStatus::SENDING, $campaign->fresh()->status);

        // Assert job was dispatched
        Queue::assertPushed(\App\Jobs\SendCampaignJob::class, function ($job) use ($campaign) {
            return $job->campaign->id === $campaign->id;
        });
    }
}
