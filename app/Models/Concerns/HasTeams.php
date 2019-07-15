<?php

namespace App\Models\Concerns;

use App\Models\Team;

trait HasTeams
{
    /**
     * User Team memberships
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teamMemberships()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * User Teams
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get the members
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class);
    }
}
