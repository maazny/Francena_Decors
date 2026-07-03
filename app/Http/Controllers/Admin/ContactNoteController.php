<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactNoteRequest;
use App\Models\Contact;
use App\Services\ContactNoteService;
use Illuminate\Http\RedirectResponse;

class ContactNoteController extends Controller
{
    protected $noteService;

    public function __construct(ContactNoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * Add an internal note to the contact record.
     */
    public function store(StoreContactNoteRequest $request, Contact $contact): RedirectResponse
    {
        $this->noteService->create($contact, $request->validated());

        return redirect()->route('admin.contacts.inquiries.show', $contact->id)
            ->with('success', 'Internal note added successfully.');
    }
}
