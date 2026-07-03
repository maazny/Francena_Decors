<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactReplyRequest;
use App\Models\Contact;
use App\Services\ContactReplyService;
use Illuminate\Http\RedirectResponse;

class ContactReplyController extends Controller
{
    protected $replyService;

    public function __construct(ContactReplyService $replyService)
    {
        $this->replyService = $replyService;
    }

    /**
     * Send a response reply to the contact.
     */
    public function store(StoreContactReplyRequest $request, Contact $contact): RedirectResponse
    {
        $file = $request->file('attachment');
        $this->replyService->create($contact, $request->validated(), $file);

        return redirect()->route('admin.contacts.inquiries.show', $contact->id)
            ->with('success', 'Reply sent successfully.');
    }
}
