<?php

namespace App\Models\Concerns;

use App\Models\Role;

trait HasRoles
{
    public function getRoleAttribute()
    {
        return $this->roles()->first()->label;
    }

    /**
     * User Roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the user has a Role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->count() === 1;
    }
}
