<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamSkillRequest;
use App\Models\TeamMember;
use App\Models\TeamSkill;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamSkillController extends Controller
{
    public function store(TeamSkillRequest $request, TeamMember $teamMember)
    {
        DB::transaction(function () use ($request, $teamMember) {
            $teamMember->skills()->create($request->validated());
        });

        TeamService::clearCache($teamMember);
        $teamMember->load('skills');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.team-members._skills-list', ['member' => $teamMember])->render(),
            ]);
        }

        return redirect()->back()->with('success', 'Skill added.');
    }

    public function update(TeamSkillRequest $request, TeamSkill $teamSkill): RedirectResponse
    {
        DB::transaction(function () use ($request, $teamSkill) {
            $teamSkill->update($request->validated());
        });

        TeamService::clearCache($teamSkill->member);

        return redirect()->back()->with('success', 'Skill updated.');
    }

    public function destroy(TeamSkill $teamSkill)
    {
        $member = $teamSkill->member;
        $teamSkill->delete();
        TeamService::clearCache($member);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Skill removed.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'exists:team_skills,id'],
            'orders.*.display_order' => ['required', 'integer'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['orders'] as $order) {
                TeamSkill::whereKey($order['id'])->update(['display_order' => $order['display_order']]);
            }
        });

        return redirect()->back()->with('success', 'Order updated.');
    }
}
