<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\ContactNote;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ContactNoteService
{
    /**
     * Create an internal annotation note for a contact inquiry.
     */
    public function create(Contact $contact, array $data): ContactNote
    {
        $userId = auth()->id() ?? User::first()?->id ?? 1;

        $data['contact_id'] = $contact->id;
        $data['user_id'] = $userId;

        $note = ContactNote::create($data);

        Log::info("Contact note added: [ID: {$note->id}] on Contact ID {$contact->id} by User ID {$userId}");

        return $note;
    }
}
