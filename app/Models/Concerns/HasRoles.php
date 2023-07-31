<?php

namespace App\Models\Concerns;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

trait HasRoles
{
    public function getRoleAttribute()
    {
        return $this->roles()->first()->id;
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
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        $id = "has_role_{$role}";

        return Cache::remember($this->cacheIdentifier($id), 86400, function () use ($role) {
            return $this->roles()->where('name', $role)->count() === 1;
        });
    }

    /**
     * Scope users by thier given roles
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $roleName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, $roleName)
    {
        $role = Role::where('name', $roleName)->first();

        return $query->whereHas('roles', function (Builder $subQuery) use ($role) {
            $subQuery->where('role_id', $role->id);
        });
    }
}
