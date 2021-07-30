<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\TeamService;
use App\Http\Forms\TeamInviteForm;
use App\Http\Forms\TeamMemberForm;
use Illuminate\Support\Facades\Gate;

class TeamMembersController extends Controller
{
    public $service;

    public function __construct(TeamService $service)
    {
        $this->service = $service;
    }

    /**
     * Display the specified team.
     *
     * @param  string  $uuid
     * @return \Illuminate\View\View
     */
    public function show($uuid)
    {
        $team = $this->service->findByUuid($uuid);

        abort_unless($team->hasActiveSubscription(), 403, 'Team does not have an active subscription.');

        abort_unless(Gate::allows('team-member', $team), 403, 'You are not a member of this team.');

        $inviteForm = app(TeamInviteForm::class)
            ->setRoute('teams.members.invite', $team)->make();

        return view('teams.show')->with(compact('team', 'inviteForm'));
    }

    /**
     * Edit a team member.
     *
     * @param  \App\Models\Team  $team
     * @param  \App\Models\User  $member
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editMember(Team $team, User $member)
    {
        abort_unless($team->hasActiveSubscription(), 403, 'Team does not have an active subscription.');

        if (! Gate::allows('team-manager', $team)) {
            return redirect()->route('teams.show', $team->id);
        }

        $teamLink = $team->route();
        $member = $team->members->find($member->id);

        $form = app(TeamMemberForm::class)
            ->setMember($member)
            ->setRoute('teams.members.update', [$team->id, $member->id])
            ->make();

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
        abort_unless($team->hasActiveSubscription(), 403, 'Team does not have an active subscription.');

        $membership = $team->members->find($member->id)->membership;

        $route = $team->route();

        try {
            if ($this->service->updateMember($membership, $member, $team, $request->except('_token'))) {
                return redirect($route)->withMessage('Successfully updated');
            }

            return redirect($route)->withMessage('Failed to update');
        } catch (Exception $e) {
            return redirect($route)->withErrors($e->getMessage());
        }
    }

    /**
     * Invite a member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inviteMember(Request $request, Team $team)
    {
        try {
            $route = $team->route();

            if ($this->service->invite($team, $request->email)) {
                return redirect($route)->with('message', 'Successfully sent invite');
            }

            return redirect($route)->withErrors(['Failed to send invite']);
        } catch (Exception $e) {
            return redirect($route)->withErrors($e->getMessage());
        }
    }

    /**
     * Leave the team.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leave(Team $team)
    {
        try {
            if ($this->service->leave($team)) {
                return redirect()->route('teams')->with('message', 'Success, your membership was removed');
            }

            return redirect()->route('teams')->withErrors(['Failed to remove membership']);
        } catch (Exception $e) {
            return redirect()->route('teams')->withErrors($e->getMessage());
        }
    }

    /**
     * Remove a team member.
     *
     * @param  \App\Models\Team  $team
     * @param  \App\Models\User  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeMember(Team $team, User $member)
    {
        $route = $team->route();

        try {
            $result = $this->service->remove($member, $team);

            if ($result) {
                return redirect($route)->with('message', 'Success, the member was removed');
            }

            return redirect($route)->withErrors(['Failed to remove member']);
        } catch (Exception $e) {
            return redirect($route)->withErrors($e->getMessage());
        }
    }
}
