<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Permissions
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

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
