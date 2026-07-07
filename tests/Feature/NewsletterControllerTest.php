<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Enums\SubscriptionStatus;
use App\Enums\SubscriberSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class NewsletterControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public subscription form AJAX submission with double opt-in.
     */
    public function test_can_subscribe_via_ajax_with_double_opt_in(): void
    {
        Mail::fake();
        config(['newsletter.double_opt_in' => true]);

        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'hello@example.com',
            'name' => 'John Doe',
            'privacy' => '1',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Thank you! Please check your email to confirm your subscription.'
            ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'hello@example.com',
            'name' => 'John Doe',
            'status' => 'pending',
            'verification_status' => 0,
        ]);

        Mail::assertSent(\App\Mail\VerifySubscriptionMail::class, function ($mail) {
            return $mail->hasTo('hello@example.com');
        });
    }

    /**
     * Test public subscription form AJAX submission with double opt-in disabled.
     */
    public function test_can_subscribe_via_ajax_without_double_opt_in(): void
    {
        Mail::fake();
        config(['newsletter.double_opt_in' => false]);

        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'hello_direct@example.com',
            'name' => 'Alice Direct',
            'privacy' => '1',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Thank you! Your subscription is now active.'
            ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'hello_direct@example.com',
            'name' => 'Alice Direct',
            'status' => 'active',
            'verification_status' => 1,
        ]);

        Mail::assertNotSent(\App\Mail\VerifySubscriptionMail::class);
    }

    /**
     * Test subscription requires valid email.
     */
    public function test_subscribe_requires_valid_email(): void
    {
        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'not-an-email',
            'name' => 'John Doe',
            'privacy' => '1',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test email verification page activates subscriber.
     */
    public function test_can_verify_subscription_page(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'status' => SubscriptionStatus::PENDING,
            'verification_status' => false,
            'verification_token' => 'test-token',
            'unsubscribe_token' => 'alice-unsub-token',
        ]);

        $response = $this->get(route('newsletter.verify', ['token' => 'test-token']));

        $response->assertOk()
            ->assertViewIs('frontend.newsletter.verify')
            ->assertViewHas('success', true);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'alice@example.com',
            'status' => 'active',
            'verification_status' => 1,
            'verification_token' => null,
        ]);
    }

    /**
     * Test invalid verification token shows fail page.
     */
    public function test_invalid_verification_token_shows_fail_page(): void
    {
        $response = $this->get(route('newsletter.verify', ['token' => 'non-existent-token']));

        $response->assertOk()
            ->assertViewIs('frontend.newsletter.verify')
            ->assertViewHas('success', false);
    }

    /**
     * Test unsubscribe form is displayed.
     */
    public function test_unsubscribe_form_displayed(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'status' => SubscriptionStatus::ACTIVE,
            'unsubscribe_token' => 'unsub-token',
        ]);

        $response = $this->get(route('newsletter.unsubscribe', ['token' => 'unsub-token']));

        $response->assertOk()
            ->assertViewIs('frontend.newsletter.unsubscribe')
            ->assertViewHas('subscriber');
    }

    /**
     * Test unsubscribe POST updates subscriber state and records reason.
     */
    public function test_unsubscribe_post_updates_state_and_records_reason(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'status' => SubscriptionStatus::ACTIVE,
            'unsubscribe_token' => 'unsub-token',
        ]);

        $response = $this->post(route('newsletter.post-unsubscribe', ['token' => 'unsub-token']), [
            'reason' => 'too_frequent',
        ]);

        $response->assertOk()
            ->assertViewIs('frontend.newsletter.unsubscribed')
            ->assertViewHas('success', true);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'bob@example.com',
            'status' => 'unsubscribed',
        ]);

        $this->assertEquals('too_frequent', $subscriber->fresh()->tags['unsubscribe_reason']);
    }

    /**
     * Test Preference Center signed URL validation.
     */
    public function test_preference_center_requires_signed_url(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Charlie',
            'email' => 'charlie@example.com',
            'status' => SubscriptionStatus::ACTIVE,
            'unsubscribe_token' => 'charlie-token',
        ]);

        // Unsigned request should fail with 403
        $response = $this->get(route('newsletter.preferences', ['token' => 'charlie-token']));
        $response->assertStatus(403);

        // Signed request should succeed
        $signedUrl = URL::signedRoute('newsletter.preferences', ['token' => 'charlie-token']);
        $response = $this->get($signedUrl);
        
        $response->assertOk()
            ->assertViewIs('frontend.newsletter.preferences')
            ->assertViewHas('subscriber');
    }

    /**
     * Test updating preferences via signed POST request.
     */
    public function test_can_update_preferences_via_signed_post(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Charlie',
            'email' => 'charlie@example.com',
            'status' => SubscriptionStatus::ACTIVE,
            'unsubscribe_token' => 'charlie-token',
            'preferred_language' => 'en',
        ]);

        $signedUrl = URL::signedRoute('newsletter.update-preferences', ['token' => 'charlie-token']);
        
        $response = $this->post($signedUrl, [
            'name' => 'Charlie Updated',
            'email' => 'charlie.new@example.com',
            'preferred_language' => 'es',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('newsletter_subscribers', [
            'id' => $subscriber->id,
            'name' => 'Charlie Updated',
            'email' => 'charlie.new@example.com',
            'preferred_language' => 'es',
        ]);
    }
}
