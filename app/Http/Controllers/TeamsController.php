<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use App\Services\TeamService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TeamsController extends Controller
{
    public $service;

    public function __construct(TeamService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $teams = $request->user()->teams;
        $memberships = $request->user()->memberships;

        return view('teams.index')->with(compact('memberships', 'teams'));
    }

    /**
     * Show the form for creating a new team.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        abort_unless(
            Gate::allows('subscribed') || auth()->user()->hasRole('admin'),
            403,
            'Subscription is required.'
        );

        return view('teams.create');
    }

    /**
     * Store a newly created team in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TeamCreateRequest $request)
    {
        abort_unless(
            Gate::allows('subscribed') || auth()->user()->hasRole('admin'),
            403,
            'Subscription is required.'
        );

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
     * @return \Illuminate\View\View
     */
    public function edit(Team $team)
    {
        $this->handleAccess($team);

        if (! Gate::allows('team-admin', $team)) {
            abort(403);
        }

        return view('teams.edit')->with(compact('team'));
    }

    /**
     * Show the form for handling members the specified team.
     *
     * @return \Illuminate\View\View
     */
    public function members(Team $team)
    {
        $this->handleAccess($team);

        if (! Gate::allows('team-admin', $team)) {
            abort(403);
        }

        return view('teams.members')->with(compact('team'));
    }

    /**
     * Update the specified team in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TeamUpdateRequest $request, Team $team)
    {
        $this->handleAccess($team);

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Team $team)
    {
        $this->handleAccess($team);

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAvatar(Request $request)
    {
        $team = Team::find($request->team);

        $this->handleAccess($team);

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

    protected function handleAccess($team)
    {
        abort_unless(
            $team->hasActiveSubscription() || auth()->user()->hasRole('admin'),
            403,
            'Subscription is required.'
        );
    }
}
