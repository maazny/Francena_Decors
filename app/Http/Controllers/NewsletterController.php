<?php

namespace App\Http\Controllers;

use App\Services\NewsletterService;
use App\Models\NewsletterSubscriber;
use App\Models\NewsletterGroup;
use App\Enums\SubscriberSource;
use App\Enums\SubscriptionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    protected NewsletterService $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Handle public AJAX or standard form subscriber signup.
     */
    public function subscribe(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'preferred_language' => 'nullable|string|max:10',
            'privacy' => 'required|accepted',
        ];

        // AJAX response check
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            try {
                $data = $request->only(['email', 'name', 'phone', 'preferred_language']);
                $data['source'] = SubscriberSource::WEBSITE;
                $this->newsletterService->subscribe($data);

                $doubleOptIn = config('newsletter.double_opt_in', true);
                $message = $doubleOptIn 
                    ? 'Thank you! Please check your email to confirm your subscription.'
                    : 'Thank you! Your subscription is now active.';

                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred. Please try again.',
                ], 500);
            }
        }

        // Standard Form Fallback
        $request->validate($rules);

        try {
            $data = $request->only(['email', 'name', 'phone', 'preferred_language']);
            $data['source'] = SubscriberSource::WEBSITE;
            $this->newsletterService->subscribe($data);

            $doubleOptIn = config('newsletter.double_opt_in', true);
            $message = $doubleOptIn 
                ? 'Subscription pending! Please check your inbox to confirm.'
                : 'Subscription active! Welcome to our newsletter.';

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Could not subscribe. Please try again.');
        }
    }

    /**
     * Verify a subscriber subscription.
     */
    public function verify(string $token): View
    {
        $subscriber = $this->newsletterService->verify($token);

        return view('frontend.newsletter.verify', [
            'success' => $subscriber !== null,
            'subscriber' => $subscriber,
        ]);
    }

    /**
     * Display the unsubscribe form.
     */
    public function unsubscribe(string $token): View
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        return view('frontend.newsletter.unsubscribe', compact('subscriber'));
    }

    /**
     * Process unsubscribe request.
     */
    public function postUnsubscribe(Request $request, string $token): View
    {
        $reason = $request->input('reason');
        if ($reason === 'other' && $request->filled('other_reason')) {
            $reason = 'Other: ' . $request->input('other_reason');
        }

        $success = $this->newsletterService->unsubscribeWithReason($token, $reason);
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        return view('frontend.newsletter.unsubscribed', [
            'success' => $success,
            'subscriber' => $subscriber,
        ]);
    }

    /**
     * Display preference management center.
     */
    public function preferences(Request $request, string $token): View
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired signature.');
        }

        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();
        $groups = $this->newsletterService->getCachedGroups();

        return view('frontend.newsletter.preferences', compact('subscriber', 'groups'));
    }

    /**
     * Update preferences.
     */
    public function updatePreferences(Request $request, string $token): RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired signature.');
        }

        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'preferred_language' => 'nullable|string|max:10',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:newsletter_groups,id',
        ]);

        $this->newsletterService->updatePreferences($subscriber, $request->all());

        // Redirect back with signed URL to retain security context
        $signedUrl = URL::signedRoute('newsletter.preferences', ['token' => $token]);

        return redirect()->to($signedUrl)->with('success', 'Preferences updated successfully.');
    }
}
