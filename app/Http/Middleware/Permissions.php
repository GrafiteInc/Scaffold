<?php

namespace App\Http\Middleware;

use Closure;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $requestPermissionCollection)
    {
        $requestPermissions = explode('|', $requestPermissionCollection);

        foreach ($requestPermissions as $accessPermission) {
            if (in_array($accessPermission, $request->user()->permissions->toArray())) {
                return $next($request);
            }
        }

        abort(401);
    }
}
