<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterCampaignTemplate;
use App\Models\NewsletterGroup;
use App\Services\NewsletterService;
use App\Enums\CampaignStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class NewsletterCampaignController extends Controller
{
    protected NewsletterService $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Display a listing of the campaigns.
     */
    public function index(Request $request): View
    {
        $query = NewsletterCampaign::with('template');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $campaigns = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.newsletter.campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create(): View
    {
        $templates = NewsletterCampaignTemplate::all();
        return view('admin.newsletter.campaigns.create', compact('templates'));
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(StoreCampaignRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title']);
        
        if (!empty($data['scheduled_at'])) {
            $data['status'] = CampaignStatus::SCHEDULED;
        } else {
            $data['status'] = CampaignStatus::DRAFT;
        }

        NewsletterCampaign::create($data);

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campaign created successfully.');
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit(NewsletterCampaign $campaign): View
    {
        $templates = NewsletterCampaignTemplate::all();
        return view('admin.newsletter.campaigns.edit', compact('campaign', 'templates'));
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(UpdateCampaignRequest $request, NewsletterCampaign $campaign): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title']);

        if (!empty($data['scheduled_at'])) {
            $data['status'] = CampaignStatus::SCHEDULED;
        } else {
            // Keep draft unless it was scheduled or sent
            if ($campaign->status === CampaignStatus::SCHEDULED) {
                $data['status'] = CampaignStatus::SCHEDULED;
            } else {
                $data['status'] = $campaign->status;
            }
        }

        $campaign->update($data);

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(NewsletterCampaign $campaign): RedirectResponse
    {
        $campaign->delete();

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }

    /**
     * Trigger sending campaign (sync or async via Service/Job).
     */
    public function send(Request $request, NewsletterCampaign $campaign): RedirectResponse
    {
        if ($campaign->status === CampaignStatus::SENT) {
            return redirect()->back()->with('error', 'Campaign has already been sent.');
        }

        $groupId = $request->input('group_id') ? (int) $request->input('group_id') : null;

        $this->newsletterService->sendCampaign($campaign, $groupId);

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campaign sending process initiated in the background.');
    }

    /**
     * Preview the parsed template email html content.
     */
    public function preview(NewsletterCampaign $campaign): View
    {
        // Simple preview replacing general placeholder values
        $htmlContent = $campaign->html_content;
        $htmlContent = str_replace(
            ['{{subscriber_name}}', '{{name}}', '[name]', '{{unsubscribe_url}}', '[unsubscribe_url]'],
            ['John Doe', 'John Doe', 'John Doe', '#', '#'],
            $htmlContent
        );

        return view('admin.newsletter.campaigns.preview', compact('campaign', 'htmlContent'));
    }

    /**
     * View logs of campaign delivery tracking details.
     */
    public function logs(NewsletterCampaign $campaign): View
    {
        $logs = $campaign->logs()->with('subscriber')->paginate(30);
        $groups = NewsletterGroup::active()->ordered()->get();

        return view('admin.newsletter.campaigns.logs', compact('campaign', 'logs', 'groups'));
    }

    /**
     * Bulk delete campaigns.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            NewsletterCampaign::whereIn('id', $ids)->delete();
            return redirect()->route('admin.newsletter.campaigns.index')
                ->with('success', 'Selected campaigns deleted successfully.');
        }

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('error', 'No campaigns selected.');
    }
}
