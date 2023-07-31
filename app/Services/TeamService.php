<?php

namespace App\Services;

use Exception;
use App\Models\Team;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Notifications\InAppNotification;

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
        $path = null;

        if (isset($payload['avatar'])) {
            $path = Storage::putFile('public/avatars', $payload['avatar'], 'public');
        }

        return $this->model->create([
            'user_id' => auth()->user()->id,
            'uuid' => Str::uuid(),
            'name' => $payload['name'],
            'avatar' => $path,
        ]);
    }

    /**
     * Update a Team.
     *
     * @return \App\Models\Team
     */
    public function update($team, $request)
    {
        $path = $team->avatar;

        if (! is_null($request->avatar)) {
            if (($request->file('avatar')->getSize() / 1024) > 10000) {
                throw new Exception('Avatar file is too big, must be below 10MB.', 1);
            }

            if ($team->avatar) {
                Storage::delete($team->avatar);
            }

            $path = Storage::putFile('public/avatars', $request->avatar, 'public');
        }

        $payload = [
            'name' => $request->name,
            'avatar' => $path,
        ];

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
    public function invite($team, $email)
    {
        $app = config('app.name');

        if ($team->members->pluck('email')->contains($email)) {
            throw new Exception('This team already has this member', 1);
        }

        $message = "You've been invited to a team on {$app} called: {$team->name}!";

        if ($invite = $team->invite($email, $message)) {
            app_notify('You sent an invite to ' . $email . ' for ' . $team->name);
        }

        return $invite;
    }

    /**
     * Leave a team.
     *
     * @param \App\Models\Team $team
     * @return bool
     */
    public function leave($team)
    {
        $user = auth()->user();

        $message = "{$user->name} has left {$team->name}.";
        $notification = new InAppNotification($message);
        $notification->isImportant();

        $team->user->notify($notification);

        return (bool) auth()->user()->memberships()->detach($team->id);
    }

    /**
     * Remove a team member.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team
     *
     * @return bool
     */
    public function remove($user, $team)
    {
        if (! Gate::allows('team-admin', $team)) {
            throw new Exception('You do not have permission to do this.', 1);
        }

        $user->memberships()->detach($team->id);

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
            $member->memberships()->detach($team->id);
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
     *
     * @return \App\Models\User|false
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
                $message = 'Your team role in ' . $team->name . ' has changed to: ' . Str::title($payload['team_role']);
                $notification = new InAppNotification($message);
                $notification->isImportant();

                $user->notify($notification);
            }

            return $user;
        }

        return false;
    }
}
