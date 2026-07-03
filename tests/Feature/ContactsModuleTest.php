<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\ContactCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ContactsModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public contact form submission.
     */
    public function test_public_user_can_submit_contact_form(): void
    {
        // Create user 1 for media service reference
        $admin = User::factory()->create(['id' => 1]);

        $category = ContactCategory::create([
            'name' => 'Sales Inquiry',
            'slug' => 'sales',
            'status' => true,
        ]);

        $response = $this->postJson(route('contact.submit'), [
            'contact_category_id' => $category->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+123456789',
            'company' => 'Doe Enterprises',
            'subject' => 'Project Inquiry',
            'message' => 'Hello, I want to discuss a decorators project.',
            'attachment' => UploadedFile::fake()->create('blueprint.pdf', 500, 'application/pdf'),
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Thank you! Your message has been sent successfully.',
        ]);

        $this->assertDatabaseHas('contacts', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'company' => 'Doe Enterprises',
            'subject' => 'Project Inquiry',
            'source' => 'website',
            'status' => 'new',
        ]);
    }

    /**
     * Test validation rules on contact form.
     */
    public function test_contact_form_requires_valid_inputs(): void
    {
        $response = $this->postJson(route('contact.submit'), [
            'name' => '',
            'email' => 'invalid-email',
            'subject' => '',
            'message' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'subject', 'message']);
    }

    /**
     * Test admin actions: update, assign, set status, set follow up, add notes and replies.
     */
    public function test_admin_can_manage_contact_lifecycle(): void
    {
        $admin = User::factory()->create(['id' => 1]);
        $agent = User::factory()->create(['name' => 'Sales Rep']);

        $contact = Contact::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'subject' => 'Consultation Request',
            'message' => 'I would like to request a consultation.',
            'source' => 'website',
            'status' => 'new',
        ]);

        // 1. Assign lead
        $response = $this->actingAs($admin)
            ->postJson(route('admin.contacts.inquiries.assign', $contact->id), [
                'assigned_to' => $agent->id,
            ]);
        $response->assertOk();
        $this->assertEquals($agent->id, $contact->fresh()->assigned_to);

        // 2. Update status
        $response = $this->actingAs($admin)
            ->postJson(route('admin.contacts.inquiries.status', $contact->id), [
                'status' => 'open',
            ]);
        $response->assertOk();
        $this->assertEquals('open', $contact->fresh()->status->value);

        // 3. Set follow up date
        $response = $this->actingAs($admin)
            ->postJson(route('admin.contacts.inquiries.follow-up', $contact->id), [
                'follow_up_at' => '2026-07-10 10:00:00',
            ]);
        $response->assertOk();
        $this->assertNotNull($contact->fresh()->follow_up_at);

        // 4. Add internal notes
        $response = $this->actingAs($admin)
            ->post(route('admin.contacts.inquiries.note', $contact->id), [
                'note' => 'Spoke briefly, scheduled follow-up.',
            ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('contact_notes', [
            'contact_id' => $contact->id,
            'user_id' => $admin->id,
            'note' => 'Spoke briefly, scheduled follow-up.',
        ]);

        // 5. Send reply (auto-sets status to contacted)
        $response = $this->actingAs($admin)
            ->post(route('admin.contacts.inquiries.reply', $contact->id), [
                'message' => 'Thank you Jane, we will contact you on the 10th.',
            ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('contact_replies', [
            'contact_id' => $contact->id,
            'user_id' => $admin->id,
            'message' => 'Thank you Jane, we will contact you on the 10th.',
        ]);

        $this->assertEquals('contacted', $contact->fresh()->status->value);
    }
}
