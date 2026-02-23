<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeamMemberRequest;
use App\Http\Requests\Admin\UpdateTeamMemberRequest;
use App\Http\Services\TeamMemberService;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class TeamMemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:team_members.list', only: ['index']),
            new Middleware('permission:team_members.create', only: ['create', 'store']),
            new Middleware('permission:team_members.update', only: ['edit', 'update']),
            new Middleware('permission:team_members.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request, TeamMemberService $teamMemberService): Response
    {
        $teamMembers = $teamMemberService->index($request);

        return Inertia::render('admin/TeamMembers/Index', [
            'teamMembers' => $teamMembers,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/TeamMembers/Create');
    }

    public function store(StoreTeamMemberRequest $request, TeamMemberService $teamMemberService): RedirectResponse
    {
        $teamMemberService->store($request->validated(), $request);

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member created successfully.');
    }

    public function edit(TeamMember $teamMember): Response
    {
        $teamMember->load('socialLinks');

        return Inertia::render('admin/TeamMembers/Edit', [
            'teamMember' => $teamMember,
        ]);
    }

    public function update(UpdateTeamMemberRequest $request, TeamMember $teamMember, TeamMemberService $teamMemberService): RedirectResponse
    {
        $teamMemberService->update($teamMember, $request->validated(), $request);

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $teamMember, TeamMemberService $teamMemberService): RedirectResponse
    {
        $teamMemberService->destroy($teamMember);

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member deleted successfully.');
    }
}
