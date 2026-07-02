<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamMemberRequest;
use App\Http\Requests\UpdateTeamMemberRequest;
use App\Models\Media;
use App\Models\TeamMember;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $department = $request->query('department');
        $featured = $request->query('featured');
        $sort = $request->query('sort', 'display_order');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $allowedSorts = ['full_name', 'display_order', 'status', 'experience_years', 'created_at'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'display_order';

        $members = TeamMember::query()
            ->with(['department', 'profilePhoto'])
            ->withTrashed()
            ->when($search, fn ($q) => $q->where(function ($b) use ($search) {
                $b->where('full_name', 'like', "%{$search}%")->orWhere('designation', 'like', "%{$search}%");
            }))
            ->when($department, fn ($q) => $q->where('department_id', $department))
            ->when($featured !== null && $featured !== '', fn ($q) => $q->where('featured', (bool) $featured))
            ->when($status !== null && $status !== '', fn ($q) => $q->where('status', (bool) $status))
            ->orderBy($sort, $direction)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $departments = TeamService::getDepartments();

        return view('admin.team-members.index', compact('members', 'departments', 'search', 'status', 'department', 'featured', 'sort', 'direction'));
    }

    public function create(): View
    {
        $member = new TeamMember([
            'display_order' => TeamMember::withTrashed()->max('display_order') + 1,
            'status' => true,
        ]);

        $mediaOptions = Media::where('is_image', true)->where('status', true)->latest()->get();
        $departments = TeamService::getDepartments();

        return view('admin.team-members.create', compact('member', 'mediaOptions', 'departments'));
    }

    public function store(StoreTeamMemberRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $payload = $this->payload($request);

            TeamMember::create($payload);
        });

        TeamService::clearCache();

        return redirect()->route('admin.team-members.index')->with('success', 'Team member created successfully.');
    }

    public function edit(TeamMember $teamMember): View
    {
        $teamMember->load(['socialLinks', 'skills', 'certifications', 'profilePhoto', 'coverPhoto']);
        $mediaOptions = Media::where('is_image', true)->where('status', true)->latest()->get();
        $departments = TeamService::getDepartments();

        return view('admin.team-members.edit', compact('teamMember', 'mediaOptions', 'departments'));
    }

    public function update(UpdateTeamMemberRequest $request, TeamMember $teamMember): RedirectResponse
    {
        DB::transaction(function () use ($request, $teamMember): void {
            $teamMember->update($this->payload($request));
        });

        TeamService::clearCache($teamMember);

        return redirect()->route('admin.team-members.edit', $teamMember)->with('success', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        $teamMember->delete();
        TeamService::clearCache($teamMember);

        return redirect()->route('admin.team-members.index')->with('success', 'Team member deleted successfully.');
    }

    public function restore(int $teamMember): RedirectResponse
    {
        $member = TeamMember::withTrashed()->findOrFail($teamMember);
        $member->restore();
        TeamService::clearCache($member);

        return redirect()->route('admin.team-members.index')->with('success', 'Team member restored successfully.');
    }

    public function toggleStatus(TeamMember $teamMember): RedirectResponse
    {
        $teamMember->update(['status' => ! $teamMember->status]);
        TeamService::clearCache($teamMember);

        return redirect()->route('admin.team-members.index')->with('success', 'Team member status updated successfully.');
    }

    public function duplicate(TeamMember $teamMember): RedirectResponse
    {
        DB::transaction(function () use ($teamMember): void {
            $copy = $teamMember->replicate();
            $copy->full_name = $teamMember->full_name.' Copy';
            $copy->slug = null;
            $copy->status = false;
            $copy->display_order = TeamMember::withTrashed()->max('display_order') + 1;
            $copy->save();
        });

        TeamService::clearCache();

        return redirect()->route('admin.team-members.index')->with('success', 'Team member duplicated successfully.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'exists:team_members,id'],
            'orders.*.display_order' => ['required', 'integer', 'between:0,9999'],
        ]);

        DB::transaction(function () use ($data): void {
            foreach ($data['orders'] as $order) {
                TeamMember::whereKey($order['id'])->update(['display_order' => $order['display_order']]);
            }
        });

        TeamService::clearCache();

        return redirect()->route('admin.team-members.index')->with('success', 'Team member ordering updated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data): void {
            $query = TeamMember::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => true]),
                'deactivate' => $query->update(['status' => false]),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        TeamService::clearCache();

        return redirect()->route('admin.team-members.index')->with('success', 'Bulk action completed successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload($request): array
    {
        $payload = $request->validated();

        $payload['slug'] = TeamService::makeSlug($request->input('full_name'), $request->input('slug'));
        $payload['featured'] = $request->boolean('featured');
        $payload['homepage_featured'] = $request->boolean('homepage_featured');
        $payload['status'] = $request->boolean('status');

        foreach ($payload as $field => $value) {
            if ($value === '') {
                $payload[$field] = null;
            }
        }

        return $payload;
    }
}
