<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\VerifySubscriptionMail;
use App\Enums\SubscriptionStatus;
use App\Enums\SubscriberSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public subscription form AJAX submission.
     */
    public function test_can_subscribe_via_ajax(): void
    {
        Mail::fake();

        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'hello@example.com',
            'name' => 'John Doe'
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
     * Test subscription requires valid email.
     */
    public function test_subscribe_requires_valid_email(): void
    {
        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'not-an-email',
            'name' => 'John Doe'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test email verification activates subscriber.
     */
    public function test_can_verify_subscription(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'status' => SubscriptionStatus::PENDING,
            'verification_status' => false,
            'verification_token' => 'test-token',
        ]);

        $response = $this->get(route('newsletter.verify', ['token' => 'test-token']));

        $response->assertRedirect('/')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'alice@example.com',
            'status' => 'active',
            'verification_status' => 1,
            'verification_token' => null,
        ]);
    }

    /**
     * Test invalid verification token returns error.
     */
    public function test_invalid_verification_token_redirects_with_error(): void
    {
        $response = $this->get(route('newsletter.verify', ['token' => 'non-existent-token']));

        $response->assertRedirect('/')
            ->assertSessionHas('error');
    }

    /**
     * Test unsubscribe deactivates subscriber.
     */
    public function test_can_unsubscribe(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'status' => SubscriptionStatus::ACTIVE,
            'unsubscribe_token' => 'unsub-token',
        ]);

        $response = $this->get(route('newsletter.unsubscribe', ['token' => 'unsub-token']));

        $response->assertRedirect('/')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'bob@example.com',
            'status' => 'unsubscribed',
        ]);
    }
}
