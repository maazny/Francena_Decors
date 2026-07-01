<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamSocialLinkRequest;
use App\Models\TeamMember;
use App\Models\TeamSocialLink;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamSocialLinkController extends Controller
{
    public function store(TeamSocialLinkRequest $request, TeamMember $teamMember)
    {
        DB::transaction(function () use ($request, $teamMember) {
            $teamMember->socialLinks()->create($request->validated());
        });

        TeamService::clearCache($teamMember);
        $teamMember->load('socialLinks');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.team-members._social-links-list', ['member' => $teamMember])->render(),
            ]);
        }

        return redirect()->back()->with('success', 'Social link added.');
    }

    public function update(TeamSocialLinkRequest $request, TeamSocialLink $teamSocialLink): RedirectResponse
    {
        DB::transaction(function () use ($request, $teamSocialLink) {
            $teamSocialLink->update($request->validated());
        });

        TeamService::clearCache($teamSocialLink->member);

        return redirect()->back()->with('success', 'Social link updated.');
    }

    public function destroy(TeamSocialLink $teamSocialLink)
    {
        $member = $teamSocialLink->member;
        $teamSocialLink->delete();
        TeamService::clearCache($member);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Social link removed.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'exists:team_social_links,id'],
            'orders.*.display_order' => ['required', 'integer'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['orders'] as $order) {
                TeamSocialLink::whereKey($order['id'])->update(['display_order' => $order['display_order']]);
            }
        });

        return redirect()->back()->with('success', 'Order updated.');
    }
}
