<?php

namespace App\Listeners;

use App\Events\RateLimitExceeded;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class DispatchRateLimitExceededOnLockout
{
    /**
     * Report login lockouts through the same event as route throttling.
     *
     * The user is always null: the lockout is keyed by whatever email was typed,
     * so treating it as the account's own behaviour would let anyone get a victim
     * disabled by failing their login on purpose.
     *
     * @return void
     */
    public function handle(Lockout $event)
    {
        $request = $event->request;
        $email = Str::lower((string) $request->input('email'));
        $key = Str::transliterate($email.'|'.$request->ip());

        RateLimitExceeded::dispatch(
            null,
            RateLimitExceeded::SOURCE_LOGIN,
            $key,
            5,
            RateLimiter::availableIn($key),
            $request->ip(),
            $request->path(),
            $email ?: null,
        );
    }
}
