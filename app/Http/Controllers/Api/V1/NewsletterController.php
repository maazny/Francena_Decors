<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\NewsletterApiRequest;
use App\Services\NewsletterService;
use Illuminate\Http\JsonResponse;

class NewsletterController extends ApiController
{
    /**
     * @var NewsletterService
     */
    protected $newsletterService;

    /**
     * NewsletterController constructor.
     */
    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Subscribe to newsletter lists.
     */
    public function store(NewsletterApiRequest $request): JsonResponse
    {
        $subscriber = $this->newsletterService->subscribe($request->validated());

        return $this->created([
            'id' => $subscriber->id,
            'name' => $subscriber->name,
            'email' => $subscriber->email,
            'status' => $subscriber->status?->value ?? $subscriber->status,
        ], 'Subscribed to newsletter successfully');
    }
}
