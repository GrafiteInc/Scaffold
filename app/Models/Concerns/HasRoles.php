<?php

namespace App\Models\Concerns;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasRoles
{
    public function getRoleAttribute()
    {
        return $this->roles()->first()->id;
    }

    /**
     * User Roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the user has a Role.
     *
     * @param  string  $role
     */
    public function hasRole($role): bool
    {
        $id = "has_role_{$role}";

        return Cache::remember($this->cacheIdentifier($id), 86400, function () use ($role) {
            return $this->roles()->where('name', $role)->count() === 1;
        });
    }

    /**
     * Scope users by their given roles
     */
    public function scopeByRole(Builder $query, string $roleName): Builder
    {
        $role = Role::where('name', $roleName)->first();

        return $query->whereHas('roles', function (Builder $subQuery) use ($role) {
            $subQuery->where('role_id', $role->id);
        });
    }
}
