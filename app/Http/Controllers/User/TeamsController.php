<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\Team;
use App\Http\Forms\TeamForm;
use App\Services\TeamService;
use App\Http\Forms\TeamInviteForm;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
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
    public function index()
    {
        $teams = auth()->user()->teams;
        $memberships = auth()->user()->teamMemberships;

        return view('user.teams.index')
            ->with('memberships', $memberships)
            ->with('teams', $teams);
    }

    /**
     * Show the form for creating a new team.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = app(TeamForm::class)->create();

        return view('user.teams.create')->with('form', $form);
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
                return redirect(route('user.teams.edit', $team->id))
                    ->with('message', 'Successfully created a team');
            }

            return redirect(route('user.teams'))->with('message', 'Failed to create a team');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
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
        if (!Gate::allows('team-admin', $team)) {
            abort(403);
        }

        $form = app(TeamForm::class)->edit($team);

        $inviteForm = app(TeamInviteForm::class)
            ->setRoute('teams.members.invite', $team)->make();

        return view('user.teams.edit')
            ->with('inviteForm', $inviteForm)
            ->with('form', $form)
            ->with('team', $team);
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
        try {
            if ($this->service->update($team, $request->except('_token'))) {
                return back()->with('message', 'Successfully updated');
            }

            return back()->with('message', 'Failed to update');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified team from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Team $team)
    {
        try {
            $result = $this->service->destroy($team);

            if ($result) {
                return redirect(route('user.teams'))->with('message', 'Successfully deleted');
            }

            return redirect(route('user.teams'))->with('message', 'Failed to delete the team');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
