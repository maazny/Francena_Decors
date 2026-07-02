<?php

namespace App\Http\Controllers;

use App\Models\TeamDepartment;
use App\Models\TeamMember;
use App\Services\TeamService;
use Illuminate\Http\Request;

class TeamController
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $department = $request->query('department');

        $query = TeamService::buildQuery(['search' => $search, 'department' => $department, 'status' => true]);

        $members = $query->active()->ordered()->paginate(12)->withQueryString();

        $departments = TeamService::getDepartments();

        return view('team.index', compact('members', 'departments', 'search', 'department'));
    }

    public function department(TeamDepartment $department)
    {
        $members = TeamMember::query()->where('department_id', $department->id)->active()->ordered()->paginate(12);

        $departments = TeamService::getDepartments();

        return view('team.department', compact('members', 'department', 'departments'));
    }

    public function show(TeamMember $teamMember)
    {
        $teamMember->load(['department', 'socialLinks', 'skills', 'certifications', 'profilePhoto', 'coverPhoto']);

        $related = TeamMember::query()->where('department_id', $teamMember->department_id)->where('id', '!=', $teamMember->id)->active()->ordered()->limit(4)->get();

        return view('team.show', compact('teamMember', 'related'));
    }
}
