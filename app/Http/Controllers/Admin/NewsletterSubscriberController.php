<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Models\NewsletterSubscriber;
use App\Models\NewsletterGroup;
use App\Enums\SubscriptionStatus;
use App\Enums\SubscriberSource;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsletterSubscriberController extends Controller
{
    /**
     * Display a listing of the subscribers.
     */
    public function index(Request $request): View
    {
        $query = NewsletterSubscriber::with('groups');

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->search($search);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('group_id')) {
            $query->whereHas('groups', function ($q) use ($request) {
                $q->where('newsletter_groups.id', $request->input('group_id'));
            });
        }
        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }

        $subscribers = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $groups = NewsletterGroup::active()->ordered()->get();

        return view('admin.newsletter.subscribers.index', compact('subscribers', 'groups'));
    }

    /**
     * Show the form for creating a new subscriber.
     */
    public function create(): View
    {
        $groups = NewsletterGroup::active()->ordered()->get();
        return view('admin.newsletter.subscribers.create', compact('groups'));
    }

    /**
     * Store a newly created subscriber in storage.
     */
    public function store(StoreSubscriberRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['source'] = SubscriberSource::ADMIN;
        $data['status'] = SubscriptionStatus::ACTIVE;
        $data['verification_status'] = true;
        $data['verified_at'] = Carbon::now();
        $data['subscribed_at'] = Carbon::now();
        $data['unsubscribe_token'] = Str::random(40);

        $subscriber = NewsletterSubscriber::create($data);

        if ($request->filled('groups')) {
            $subscriber->groups()->sync($request->input('groups'));
        }

        return redirect()->route('admin.newsletter.subscribers.index')
            ->with('success', 'Subscriber created successfully.');
    }

    /**
     * Show the form for editing the specified subscriber.
     */
    public function edit(NewsletterSubscriber $subscriber): View
    {
        $groups = NewsletterGroup::active()->ordered()->get();
        return view('admin.newsletter.subscribers.edit', compact('subscriber', 'groups'));
    }

    /**
     * Update the specified subscriber in storage.
     */
    public function update(UpdateSubscriberRequest $request, NewsletterSubscriber $subscriber): RedirectResponse
    {
        $data = $request->validated();
        
        $subscriber->update($data);

        if (isset($data['groups'])) {
            $subscriber->groups()->sync($data['groups']);
        } else {
            $subscriber->groups()->detach();
        }

        return redirect()->route('admin.newsletter.subscribers.index')
            ->with('success', 'Subscriber updated successfully.');
    }

    /**
     * Remove the specified subscriber from storage (soft delete).
     */
    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletter.subscribers.index')
            ->with('success', 'Subscriber deleted successfully.');
    }

    /**
     * Toggle status of subscriber between active and unsubscribed.
     */
    public function toggleStatus(NewsletterSubscriber $subscriber): RedirectResponse
    {
        if ($subscriber->status === SubscriptionStatus::ACTIVE) {
            $subscriber->update([
                'status' => SubscriptionStatus::UNSUBSCRIBED,
                'unsubscribed_at' => Carbon::now(),
            ]);
        } else {
            $subscriber->update([
                'status' => SubscriptionStatus::ACTIVE,
                'subscribed_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('admin.newsletter.subscribers.index')
            ->with('success', 'Subscriber status toggled successfully.');
    }

    /**
     * Bulk delete subscribers.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            NewsletterSubscriber::whereIn('id', $ids)->delete();
            return redirect()->route('admin.newsletter.subscribers.index')
                ->with('success', 'Selected subscribers deleted successfully.');
        }

        return redirect()->route('admin.newsletter.subscribers.index')
            ->with('error', 'No subscribers selected.');
    }

    /**
     * Bulk update status of subscribers.
     */
    public function bulkStatus(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status');

        if (!empty($ids) && $status) {
            NewsletterSubscriber::whereIn('id', $ids)->update([
                'status' => $status,
                'updated_at' => Carbon::now(),
            ]);
            return redirect()->route('admin.newsletter.subscribers.index')
                ->with('success', 'Selected subscribers status updated successfully.');
        }

        return redirect()->route('admin.newsletter.subscribers.index')
            ->with('error', 'Invalid bulk status request.');
    }
}
