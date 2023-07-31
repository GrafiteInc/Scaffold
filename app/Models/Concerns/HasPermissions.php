<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasPermissions
{
    /**
     * User Permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionsAttribute()
    {
        $userPermissions = collect();

        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                $userPermissions->push($permission);
            }
        }

        // Here we're ensuring that if a user has all the permissions
        // of a group of permissions then they have the plain worded permission.
        foreach (config('permissions', []) as $group => $permissions) {
            $count = $userPermissions->filter(function ($key) use ($group) {
                return Str::contains($key, $group);
            })->count();

            if (count($permissions) === $count) {
                $userPermissions->push($group);
            }
        }

        return $userPermissions;
    }

    /**
     * Check if user has permission
     *
     * @param  string  $requestedPermission
     * @return bool
     */
    public function hasPermission($requestedPermissions)
    {
        if (! is_array($requestedPermissions)) {
            $requestedPermissions = [$requestedPermissions];
        }

        return collect($this->permissions)->filter(function ($permission) use ($requestedPermissions) {
            return in_array($permission, $requestedPermissions);
        })->count() === count($requestedPermissions);
    }
}
