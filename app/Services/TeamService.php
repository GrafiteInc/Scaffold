<?php

namespace App\Services;

use Exception;
use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use App\Notifications\InAppNotification;

class TeamService
{
    /**
     * Team model
     *
     * @var Team
     */
    public $model;

    public function __construct(Team $model)
    {
        $this->model = $model;
    }

    /**
     * Create a Team
     *
     * @return \App\Models\Team
     */
    public function create($payload)
    {
        return $this->model->create([
            'user_id' => auth()->user()->id,
            'name' => $payload['name'],
        ]);
    }

    /**
     * Update a Team
     *
     * @return \App\Models\Team
     */
    public function update($team, $payload)
    {
        $team->update($payload);

        return $team->fresh();
    }

    /**
     * Invite a user to a team
     *
     * @param \App\Models\Team $team
     * @param string $email
     *
     * @return \App\Models\Invite
     */
    public function invite($teamId, $email)
    {
        $team = $this->model->find($teamId);

        $app = config('app.name');

        $message = "You've been invited to a team on {$app} called: {$team->name}!";

        return $team->invite($email, $message);
    }

    /**
     * Leave a team
     *
     * @param int $teamId
     * @return bool
     */
    public function leave($teamId)
    {
        $user = auth()->user();
        $team = $this->model->find($teamId);

        $message = "{$user->name} has left {$team->name}.";
        $notification = new InAppNotification($message);
        $notification->isImportant();

        $team->user->notify($notification);

        return auth()->user()->teamMemberships()->detach($teamId);
    }

    /**
     * Remove a team member
     *
     * @param \App\Models\Team $team
     * @param \App\Models\User $user
     * @return bool
     */
    public function remove($team, $user)
    {
        if (Gate::allows('admin-team', $team)) {
            throw new Exception("You do not have permission to do this.", 1);
        }

        return $user->teamMemberships()->detach($team->id);
    }

    /**
     * Delete a team
     *
     * @param Team $team
     *
     * @return bool
     */
    public function destroy($team)
    {
        $team->members->each(function ($member) use ($team) {
            $message = "{$team->name} has been deleted by it's admin.";
            $notification = new InAppNotification($message);
            $notification->isImportant();

            $member->notify($notification);
            $member->teamMemberships->detach($team->id);
        });

        $team->invites()->delete();

        return $team->delete();
    }
}
