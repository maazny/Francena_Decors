<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamDepartmentRequest;
use App\Http\Requests\UpdateTeamDepartmentRequest;
use App\Models\TeamDepartment;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TeamDepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $sort = $request->query('sort', 'display_order');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $allowedSorts = ['name', 'display_order', 'status', 'created_at'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'display_order';

        $departments = TeamDepartment::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($status !== null && $status !== '', fn ($q) => $q->where('status', (bool) $status))
            ->orderBy($sort, $direction)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.team-departments.index', compact('departments', 'search', 'status', 'sort', 'direction'));
    }

    public function create(): View
    {
        $department = new TeamDepartment(['display_order' => TeamDepartment::withTrashed()->max('display_order') + 1, 'status' => true]);

        return view('admin.team-departments.create', compact('department'));
    }

    public function store(StoreTeamDepartmentRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            TeamDepartment::create([
                'name' => $request->input('name'),
                'slug' => TeamService::makeSlug($request->input('name'), $request->input('slug')),
                'description' => $request->input('description'),
                'display_order' => $request->input('display_order', 0),
                'status' => $request->boolean('status'),
            ]);
        });

        TeamService::clearCache();

        return redirect()->route('admin.team-departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(TeamDepartment $teamDepartment): View
    {
        return view('admin.team-departments.edit', compact('teamDepartment'));
    }

    public function update(UpdateTeamDepartmentRequest $request, TeamDepartment $teamDepartment): RedirectResponse
    {
        DB::transaction(function () use ($request, $teamDepartment): void {
            $teamDepartment->update([
                'name' => $request->input('name'),
                'slug' => TeamService::makeSlug($request->input('name'), $request->input('slug')),
                'description' => $request->input('description'),
                'display_order' => $request->input('display_order', 0),
                'status' => $request->boolean('status'),
            ]);
        });

        TeamService::clearCache(null, $teamDepartment);

        return redirect()->route('admin.team-departments.edit', $teamDepartment)->with('success', 'Department updated successfully.');
    }

    public function destroy(TeamDepartment $teamDepartment): RedirectResponse
    {
        $teamDepartment->delete();
        TeamService::clearCache(null, $teamDepartment);

        return redirect()->route('admin.team-departments.index')->with('success', 'Department deleted successfully.');
    }

    public function restore(int $department): RedirectResponse
    {
        $dep = TeamDepartment::withTrashed()->findOrFail($department);
        $dep->restore();
        TeamService::clearCache(null, $dep);

        return redirect()->route('admin.team-departments.index')->with('success', 'Department restored successfully.');
    }

    public function toggleStatus(TeamDepartment $teamDepartment): RedirectResponse
    {
        $teamDepartment->update(['status' => ! $teamDepartment->status]);
        TeamService::clearCache(null, $teamDepartment);

        return redirect()->route('admin.team-departments.index')->with('success', 'Department status updated successfully.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'exists:team_departments,id'],
            'orders.*.display_order' => ['required', 'integer', 'between:0,9999'],
        ]);

        DB::transaction(function () use ($data): void {
            foreach ($data['orders'] as $order) {
                TeamDepartment::whereKey($order['id'])->update(['display_order' => $order['display_order']]);
            }
        });

        TeamService::clearCache();

        return redirect()->route('admin.team-departments.index')->with('success', 'Department ordering updated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data): void {
            $query = TeamDepartment::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => true]),
                'deactivate' => $query->update(['status' => false]),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        TeamService::clearCache();

        return redirect()->route('admin.team-departments.index')->with('success', 'Bulk action completed successfully.');
    }
}
