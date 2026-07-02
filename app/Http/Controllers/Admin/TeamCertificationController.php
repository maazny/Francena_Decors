<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamCertificationRequest;
use App\Models\TeamCertification;
use App\Models\TeamMember;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamCertificationController extends Controller
{
    public function store(TeamCertificationRequest $request, TeamMember $teamMember)
    {
        DB::transaction(function () use ($request, $teamMember) {
            $teamMember->certifications()->create($request->validated());
        });

        TeamService::clearCache($teamMember);
        $teamMember->load('certifications');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.team-members._certifications-list', ['member' => $teamMember])->render(),
            ]);
        }

        return redirect()->back()->with('success', 'Certification added.');
    }

    public function update(TeamCertificationRequest $request, TeamCertification $teamCertification): RedirectResponse
    {
        DB::transaction(function () use ($request, $teamCertification) {
            $teamCertification->update($request->validated());
        });

        TeamService::clearCache($teamCertification->member);

        return redirect()->back()->with('success', 'Certification updated.');
    }

    public function destroy(TeamCertification $teamCertification)
    {
        $member = $teamCertification->member;
        $teamCertification->delete();
        TeamService::clearCache($member);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Certification removed.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'exists:team_certifications,id'],
            'orders.*.display_order' => ['required', 'integer'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['orders'] as $order) {
                TeamCertification::whereKey($order['id'])->update(['display_order' => $order['display_order']]);
            }
        });

        return redirect()->back()->with('success', 'Order updated.');
    }
}
