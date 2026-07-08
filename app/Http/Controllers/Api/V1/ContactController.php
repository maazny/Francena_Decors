<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\ContactApiRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends ApiController
{
    /**
     * @var ContactService
     */
    protected $contactService;

    /**
     * ContactController constructor.
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Submit a contact inquiry.
     */
    public function store(ContactApiRequest $request): JsonResponse
    {
        $contact = $this->contactService->create(
            $request->validated(),
            $request->file('attachment')
        );

        return $this->created([
            'id' => $contact->id,
            'name' => $contact->name,
            'email' => $contact->email,
            'subject' => $contact->subject,
        ], 'Contact inquiry submitted successfully');
    }
}
