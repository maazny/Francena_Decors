<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\ContactReply;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactReplyService
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Send and log a reply for a contact inquiry.
     */
    public function create(Contact $contact, array $data, ?UploadedFile $file = null): ContactReply
    {
        return DB::transaction(function () use ($contact, $data, $file) {
            $userId = auth()->id() ?? User::first()?->id ?? 1;

            if ($file) {
                $media = $this->mediaService->storeFile($file, 'replies', $userId);
                $data['attachment'] = $media->id;
            }

            $data['contact_id'] = $contact->id;
            $data['user_id'] = $userId;

            $reply = ContactReply::create($data);

            // Lead status updates: change status to contacted and mark as read
            $contact->update([
                'status' => \App\Enums\ContactStatus::CONTACTED,
                'is_read' => true,
            ]);

            // Dispatch Notification Email
            try {
                \Illuminate\Support\Facades\Notification::route('mail', $contact->email)
                    ->notify(new \App\Notifications\ContactReplyNotification($reply));
            } catch (\Exception $e) {
                Log::error("Failed sending contact reply email notification: " . $e->getMessage());
            }

            Log::info("Contact reply sent: [ID: {$reply->id}] relative to Contact ID {$contact->id} by User ID {$userId}");

            return $reply;
        });
    }
}
