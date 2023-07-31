<?php

namespace App\Models\Concerns;

use App\Models\Team;

trait HasTeams
{
    /**
     * User Team memberships.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function memberships()
    {
        return $this->belongsToMany(Team::class)
            ->as('membership')
            ->withPivot('team_role');
    }

    /**
     * User Teams.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * The teams the user can access
     *
     * @return void
     */
    public function teamsUserCanAccess()
    {
        return $this->teams->merge($this->memberships)->unique();
    }
}
