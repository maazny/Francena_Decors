<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactService
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Store a newly created contact inquiry in the database.
     */
    public function create(array $data, ?UploadedFile $file = null): Contact
    {
        return DB::transaction(function () use ($data, $file) {
            if ($file) {
                // Associate upload with first admin or user ID 1
                $adminId = User::first()?->id ?? 1;
                $media = $this->mediaService->storeFile($file, 'contacts', $adminId);
                $data['attachment'] = $media->id;
            }

            // Set default client metadata if available
            $data['ip_address'] = $data['ip_address'] ?? request()->ip();
            $data['user_agent'] = $data['user_agent'] ?? request()->userAgent();
            $data['status'] = $data['status'] ?? \App\Enums\ContactStatus::NEW;
            $data['source'] = $data['source'] ?? \App\Enums\ContactSource::WEBSITE;

            $contact = Contact::create($data);

            Log::info("Contact inquiry created: [ID: {$contact->id}] Name: {$contact->name}, Subject: {$contact->subject}");

            return $contact;
        });
    }

    /**
     * Update an existing contact inquiry.
     */
    public function update(Contact $contact, array $data, ?UploadedFile $file = null): Contact
    {
        return DB::transaction(function () use ($contact, $data, $file) {
            if ($file) {
                $adminId = auth()->id() ?? User::first()?->id ?? 1;
                $media = $this->mediaService->storeFile($file, 'contacts', $adminId);
                $data['attachment'] = $media->id;
            }

            $oldStatus = $contact->status;
            $contact->update($data);

            if (isset($data['status']) && $contact->status !== $oldStatus) {
                Log::info("Contact status updated: [ID: {$contact->id}] from " . ($oldStatus instanceof \App\Enums\ContactStatus ? $oldStatus->value : $oldStatus) . " to " . $contact->status->value);
            }

            return $contact;
        });
    }

    /**
     * Assign a contact inquiry to an admin user.
     */
    public function assign(Contact $contact, ?int $userId): Contact
    {
        $contact->update([
            'assigned_to' => $userId,
        ]);

        $userName = $userId ? (User::find($userId)?->name ?? 'User ID ' . $userId) : 'Unassigned';
        Log::info("Contact inquiry assigned: [ID: {$contact->id}] to {$userName}");

        return $contact;
    }

    /**
     * Update status and log.
     */
    public function updateStatus(Contact $contact, \App\Enums\ContactStatus $status): Contact
    {
        $oldStatus = $contact->status;
        $contact->update(['status' => $status]);

        Log::info("Contact status changed: [ID: {$contact->id}] from " . ($oldStatus instanceof \App\Enums\ContactStatus ? $oldStatus->value : $oldStatus) . " to {$status->value}");

        return $contact;
    }

    /**
     * Set follow up reminder date.
     */
    public function setFollowUp(Contact $contact, ?string $date): Contact
    {
        $contact->update([
            'follow_up_at' => $date ? \Carbon\Carbon::parse($date) : null,
        ]);

        Log::info("Contact follow-up date updated: [ID: {$contact->id}] to " . ($date ?? 'Cleared'));

        return $contact;
    }

    /**
     * Toggle read status.
     */
    public function toggleRead(Contact $contact): Contact
    {
        $contact->update([
            'is_read' => ! $contact->is_read,
        ]);
        return $contact;
    }

    /**
     * Restore a soft-deleted contact inquiry.
     */
    public function restore(int $id): bool
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);
        $res = $contact->restore();
        Log::info("Contact inquiry restored: [ID: {$id}]");
        return $res;
    }

    /**
     * Bulk delete records.
     */
    public function bulkDelete(array $ids): int
    {
        $count = Contact::whereIn('id', $ids)->delete();
        Log::info("Contact inquiries deleted (Bulk): Count {$count}");
        return $count;
    }

    /**
     * Bulk update status.
     */
    public function bulkStatus(array $ids, string $status): int
    {
        $count = Contact::whereIn('id', $ids)->update(['status' => $status]);
        Log::info("Contact inquiries status updated (Bulk): Count {$count} to {$status}");
        return $count;
    }

    /**
     * Bulk assign records.
     */
    public function bulkAssign(array $ids, ?int $userId): int
    {
        $count = Contact::whereIn('id', $ids)->update(['assigned_to' => $userId]);
        $userName = $userId ? (User::find($userId)?->name ?? 'User ID ' . $userId) : 'Unassigned';
        Log::info("Contact inquiries assigned (Bulk): Count {$count} to {$userName}");
        return $count;
    }
}
