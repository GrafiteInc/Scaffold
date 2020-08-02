<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasMembers;
use App\Http\Forms\TeamInviteForm;
use App\Http\Forms\TeamMemberForm;
use App\Models\Team;
use App\Models\User;
use App\Services\TeamService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeamMembersController extends Controller
{
    use HasMembers;

    public function __construct(TeamService $service)
    {
        $this->service = $service;
    }

    /**
     * Display the specified team.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $team = $this->service->findByUuid($uuid);

        if (! Gate::allows('team-member', $team)) {
            abort(403);
        }

        $inviteForm = app(TeamInviteForm::class)
            ->setRoute('teams.members.invite', $team)->make();

        return view('teams.show')->with(compact('team', 'inviteForm'));
    }

    /**
     * Edit a team member.
     *
     * @param  \App\Models\Team  $team
     * @param  \App\Models\User  $member
     * @return \Illuminate\Http\Response
     */
    public function editMember(Team $team, User $member)
    {
        if (! Gate::allows('team-manager', $team)) {
            return redirect()->route('teams.show', $team->id);
        }

        $teamLink = route('teams.show', $team->uuid);
        $member = $team->members->find($member->id);

        $form = app(TeamMemberForm::class)
            ->setMember($member)
            ->setRoute('teams.members.update', [$team->id, $member->id])
            ->make();

        if (Gate::allows('team-admin', $team)) {
            $teamLink = route('teams.edit', $team->id);
        }

        return view('teams.member-edit')->with(compact('teamLink', 'form', 'member', 'team'));
    }

    /**
     * Update a members information.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Team $team
     * @param \App\Models\User $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMember(Request $request, Team $team, User $member)
    {
        $membership = $team->members->find($member->id)->membership;

        //TODO -> whats up with this?
        if (Gate::allows('team-manager', $team)) {
            $teamLink = route('teams.edit', $team->id);
        }

        try {
            if ($this->service->updateMember($membership, $member, $team, $request->except('_token'))) {
                return redirect()->back()->withMessage('Successfully updated');
            }

            return redirect()->back()->withMessage('Failed to update');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
