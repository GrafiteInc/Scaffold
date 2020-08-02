<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Team;
use App\Http\Forms\TeamForm;
use Illuminate\Http\Request;
use App\Services\TeamService;
use App\Http\Forms\TeamInviteForm;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateRequest;

class TeamsController extends Controller
{
    public function __construct(TeamService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $teams = $request->user()->teams;
        $memberships = $request->user()->teamMemberships;

        return view('teams.index')->with(compact('memberships', 'teams'));
    }

    /**
     * Show the form for creating a new team.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = app(TeamForm::class)->create();

        return view('teams.create')->with(compact('form'));
    }

    /**
     * Store a newly created team in storage.
     *
     * @param  \Illuminate\Http\TeamCreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TeamCreateRequest $request)
    {
        try {
            $team = $this->service->create($request->except('_token'));

            if ($team) {
                return redirect()->route('teams.edit', $team->id)
                    ->withMessage('Successfully created a team');
            }

            return redirect()->route('teams')->withMessage('Failed to create a team');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified team.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        if (! Gate::allows('team-admin', $team)) {
            abort(403);
        }

        $form = app(TeamForm::class)->edit($team);

        $inviteForm = app(TeamInviteForm::class)
            ->setRoute('teams.members.invite', $team)->make();

        return view('teams.edit')->with(compact('inviteForm', 'form', 'team'));
    }

    /**
     * Show the form for handling members the specified team.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function members(Team $team)
    {
        if (! Gate::allows('team-admin', $team)) {
            abort(403);
        }

        $inviteForm = app(TeamInviteForm::class)
            ->setRoute('teams.members.invite', $team)->make();

        return view('teams.members')->with(compact('inviteForm', 'team'));
    }


    /**
     * Update the specified team in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TeamUpdateRequest $request, Team $team)
    {
        if (Gate::denies('team-admin', $team)) {
            return redirect()->back()->withErrors(['You do not have permission to do this.']);
        }

        try {
            if ($this->service->update($team, $request)) {
                return redirect()->back()->withMessage('Successfully updated');
            }

            return redirect()->back()->withMessage('Failed to update');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified team from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Team $team)
    {
        if (Gate::denies('team-admin', $team)) {
            return redirect()->back()->withErrors(['You do not have permission to do this.']);
        }

        try {
            $result = $this->service->destroy($team);

            if ($result) {
                return redirect()->route('teams')->withMessage('Successfully deleted');
            }

            return redirect()->route('teams')->withMessage('Failed to delete the team');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified team avatar from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAvatar(Request $request)
    {
        $team = Team::find($request->team);

        if (Gate::denies('team-admin', $team)) {
            return redirect()->back()->withErrors(['You do not have permission to do this.']);
        }

        try {
            Storage::delete($team->avatar);

            if ($team->update([
                'avatar' => null,
            ])) {
                return redirect()->back()->withMessage('Successfully deleted the team avatar.');
            }

            return redirect()->back()->withMessage('Failed to delete the team avatar.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
