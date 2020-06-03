<?php

namespace App\Services;

use App\Models\Team;
use App\Notifications\InAppNotification;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class TeamService
{
    /**
     * Team model.
     *
     * @var Team
     */
    public $model;

    public function __construct(Team $model)
    {
        $this->model = $model;
    }

    /**
     * Find a team by uuid.
     *
     * @param string $uuid
     * @return \App\Models\Team
     */
    public function findByUuid($uuid)
    {
        return $this->model->where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Create a Team.
     *
     * @return \App\Models\Team
     */
    public function create(array $payload)
    {
        return $this->model->create([
            'user_id' => auth()->user()->id,
            'uuid' => Str::uuid(),
            'name' => $payload['name'],
        ]);
    }

    /**
     * Update a Team.
     *
     * @return \App\Models\Team
     */
    public function update($team, $payload)
    {
        $team->update($payload);

        return $team->fresh();
    }

    /**
     * Invite a user to a team.
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

        if ($team->members->pluck('email')->contains($email)) {
            throw new Exception('This team already has this member', 1);
        }

        $message = "You've been invited to a team on {$app} called: {$team->name}!";

        if ($invite = $team->invite($email, $message)) {
            app_notify('You sent an invite to '.$email.' for '.$team->name);
        }

        return $invite;
    }

    /**
     * Leave a team.
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
     * Remove a team member.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team
     * @return bool
     */
    public function remove($user, $team)
    {
        $team = $this->model->find($team);

        if (! Gate::allows('team-admin', $team)) {
            throw new Exception('You do not have permission to do this.', 1);
        }

        $user->teamMemberships()->detach($team->id);

        return true;
    }

    /**
     * Delete a team.
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
            $member->teamMemberships()->detach($team->id);
        });

        $team->invites()->delete();

        return $team->delete();
    }

    /**
     * Update a members information.
     *
     * @param \Illuminate\Database\Eloquent\Relations\Pivot $membership
     * @param \App\Models\User $user
     * @param \App\Models\Team $team
     * @param array $payload
     * @return \App\Models\User
     */
    public function updateMember($membership, $user, $team, $payload)
    {
        $originalRole = $membership->team_role;

        if (
            $membership->forceFill([
                'team_role' => $payload['team_role'],
            ])->save()
        ) {
            if ($originalRole !== $payload['team_role']) {
                $message = 'Your team role in '.$team->name.' has changed to: '.Str::title($payload['team_role']);
                $notification = new InAppNotification($message);
                $notification->isImportant();

                $user->notify($notification);
            }

            return $user;
        }

        return false;
    }
}
