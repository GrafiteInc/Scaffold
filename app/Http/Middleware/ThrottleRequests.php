<?php

namespace App\Http\Middleware;

use App\Events\RateLimitExceeded;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequests;

/**
 * Laravel's throttle, plus a RateLimitExceeded event whenever a request is
 * turned away. Registered as the `throttle` alias in bootstrap/app.php, so it
 * covers every `throttle:*` route and throttleApi().
 *
 * If the app moves to throttleWithRedis(), extend ThrottleRequestsWithRedis instead.
 */
class ThrottleRequests extends BaseThrottleRequests
{
    /**
     * Create a 'too many attempts' exception.
     *
     * @param  Request  $request
     * @param  string  $key
     * @param  int  $maxAttempts
     * @param  callable|null  $responseCallback
     * @return ThrottleRequestsException|HttpResponseException
     */
    protected function buildException($request, $key, $maxAttempts, $responseCallback = null)
    {
        $user = $request->user();

        RateLimitExceeded::dispatch(
            $user instanceof User ? $user : null,
            RateLimitExceeded::SOURCE_ROUTE,
            $key,
            (int) $maxAttempts,
            (int) $this->getTimeUntilNextRetry($key),
            $request->ip(),
            $request->path(),
        );

        return parent::buildException($request, $key, $maxAttempts, $responseCallback);
    }
}
