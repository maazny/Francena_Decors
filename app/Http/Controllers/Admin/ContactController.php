<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContactPriority;
use App\Enums\ContactSource;
use App\Enums\ContactStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Models\ContactCategory;
use App\Models\User;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display filtered list of contact inquiries.
     */
    public function index(Request $request): View
    {
        $query = Contact::withTrashed()->with(['category', 'user']);

        // Search Keyword
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('category_id')) {
            $query->where('contact_category_id', $request->input('category_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }
        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->input('assigned_to'));
        }
        if ($request->filled('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        // Date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $contacts = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $categories = ContactCategory::active()->ordered()->get();
        $users = User::orderBy('name')->get();

        return view('admin.contacts.inquiries.index', compact('contacts', 'categories', 'users'));
    }

    /**
     * Display a specific contact message thread (and auto-mark as read).
     */
    public function show(Contact $contact): View
    {
        // Auto-mark read on access
        if (! $contact->is_read) {
            $this->contactService->toggleRead($contact);
        }

        $contact->load([
            'category',
            'user',
            'attachmentMedia',
            'replies.user',
            'replies.attachmentMedia',
            'notes.user',
        ]);

        $users = User::orderBy('name')->get();

        return view('admin.contacts.inquiries.show', compact('contact', 'users'));
    }

    /**
     * Store a manually entered contact (e.g. from Phone Call).
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['source'] = $data['source'] ?? ContactSource::ADMIN;

        $file = $request->file('attachment');
        $this->contactService->create($data, $file);

        return redirect()->route('admin.contacts.inquiries.index')
            ->with('success', 'Contact inquiry created successfully.');
    }

    /**
     * Update an existing contact inquiry.
     */
    public function update(UpdateContactRequest $request, Contact $contact): RedirectResponse
    {
        $file = $request->file('attachment');
        $this->contactService->update($contact, $request->validated(), $file);

        return redirect()->route('admin.contacts.inquiries.show', $contact->id)
            ->with('success', 'Contact inquiry updated successfully.');
    }

    /**
     * Soft delete an inquiry.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();
        return redirect()->route('admin.contacts.inquiries.index')
            ->with('success', 'Contact inquiry soft deleted successfully.');
    }

    /**
     * Restore a soft-deleted inquiry.
     */
    public function restore(int $id): RedirectResponse
    {
        $this->contactService->restore($id);
        return redirect()->route('admin.contacts.inquiries.index')
            ->with('success', 'Contact inquiry restored successfully.');
    }

    /**
     * Assign inquiry to an admin user.
     */
    public function assign(Request $request, Contact $contact): JsonResponse
    {
        $request->validate(['assigned_to' => ['nullable', 'exists:users,id']]);
        $this->contactService->assign($contact, $request->input('assigned_to'));

        return response()->json([
            'success' => true,
            'message' => 'Lead assignment updated successfully.',
        ]);
    }

    /**
     * Update status.
     */
    public function updateStatus(Request $request, Contact $contact): JsonResponse
    {
        $request->validate(['status' => ['required', new \Illuminate\Validation\Rules\Enum(ContactStatus::class)]]);
        $status = ContactStatus::from($request->input('status'));
        $this->contactService->updateStatus($contact, $status);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ]);
    }

    /**
     * Set follow up schedule reminder date.
     */
    public function setFollowUp(Request $request, Contact $contact): JsonResponse
    {
        $request->validate(['follow_up_at' => ['nullable', 'date']]);
        $this->contactService->setFollowUp($contact, $request->input('follow_up_at'));

        return response()->json([
            'success' => true,
            'message' => 'Follow up schedule updated successfully.',
        ]);
    }

    /**
     * Toggle read/unread status.
     */
    public function toggleRead(Contact $contact): JsonResponse
    {
        $contact = $this->contactService->toggleRead($contact);
        return response()->json([
            'success' => true,
            'is_read' => $contact->is_read,
            'message' => 'Read status toggled successfully.',
        ]);
    }

    /**
     * Bulk Delete.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->contactService->bulkDelete($ids);
        return response()->json([
            'success' => true,
            'message' => "{$count} inquiries deleted successfully.",
        ]);
    }

    /**
     * Bulk Status change.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $request->validate(['status' => ['required', new \Illuminate\Validation\Rules\Enum(ContactStatus::class)]]);
        $status = $request->input('status');

        $count = $this->contactService->bulkStatus($ids, $status);
        return response()->json([
            'success' => true,
            'message' => "{$count} inquiries status updated to {$status}.",
        ]);
    }

    /**
     * Bulk Assign.
     */
    public function bulkAssign(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $request->validate(['assigned_to' => ['nullable', 'exists:users,id']]);
        $userId = $request->input('assigned_to');

        $count = $this->contactService->bulkAssign($ids, $userId);
        return response()->json([
            'success' => true,
            'message' => "{$count} inquiries assigned successfully.",
        ]);
    }
}
