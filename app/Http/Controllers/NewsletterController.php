<?php

namespace App\Http\Controllers;

use App\Services\NewsletterService;
use App\Enums\SubscriberSource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    protected NewsletterService $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Handle public AJAX subscriber signup.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only(['email', 'name']);
        $data['source'] = SubscriberSource::WEBSITE;

        try {
            $this->newsletterService->subscribe($data);

            return response()->json([
                'success' => true,
                'message' => 'Thank you! Please check your email to confirm your subscription.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during subscription. Please try again.',
            ], 500);
        }
    }

    /**
     * Verify a subscriber subscription.
     */
    public function verify(string $token): RedirectResponse
    {
        $subscriber = $this->newsletterService->verify($token);

        if ($subscriber) {
            return redirect()->to('/')->with('success', 'Your newsletter subscription has been verified successfully!');
        }

        return redirect()->to('/')->with('error', 'Invalid or expired verification token.');
    }

    /**
     * Unsubscribe a subscriber.
     */
    public function unsubscribe(string $token): RedirectResponse
    {
        $success = $this->newsletterService->unsubscribe($token);

        if ($success) {
            return redirect()->to('/')->with('success', 'You have been successfully unsubscribed from our newsletter.');
        }

        return redirect()->to('/')->with('error', 'Invalid unsubscribe link or you have already unsubscribed.');
    }
}
