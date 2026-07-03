<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Services\ContactService;
use App\Models\ContactCategory;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display the public contact page.
     */
    public function index(): View
    {
        $siteSetting = \Illuminate\Support\Facades\Cache::remember('contact.site_settings', 3600, function () {
            return SiteSetting::first() ?? new SiteSetting();
        });
        $categories = \Illuminate\Support\Facades\Cache::remember('contact.active_categories', 3600, function () {
            return ContactCategory::active()->ordered()->get();
        });
        return view('contact.index', compact('siteSetting', 'categories'));
    }

    /**
     * Handle public contact form submission.
     */
    public function submit(StoreContactRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['source'] = \App\Enums\ContactSource::WEBSITE;
        $data['status'] = \App\Enums\ContactStatus::NEW;

        $file = $request->file('attachment');
        $contact = $this->contactService->create($data, $file);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your message has been sent successfully.',
            'contact_id' => $contact->id,
        ]);
    }
}
