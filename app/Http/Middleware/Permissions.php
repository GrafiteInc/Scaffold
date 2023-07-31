<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, $requestPermissionCollection)
    {
        if (Gate::forUser($request->user())->allows('has-permissions', [$requestPermissionCollection])) {
            return $next($request);
        }

        abort(401);
    }
}
