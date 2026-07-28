<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Activity
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        activity('Standard User Action');

        return $next($request);
    }
}
