<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class TwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->notConfirmedTwoFactor()) {
            if ($user->usesTwoFactor('email')) {
                $user->setAndSendTwoFactorForEmail();
                return redirect(route('verification.two-factor'));
            }

            if ($user->usesTwoFactor('authenticator')) {
                $authenticator = app(Authenticator::class)->boot($request);

                if (! $authenticator->isAuthenticated()) {
                    return redirect(route('verification.two-factor'));
                }
            }
        }

        return $next($request);
    }
}
