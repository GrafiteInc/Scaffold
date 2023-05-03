<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class TwoFactorController extends Controller
{
    public function showForm(Request $request)
    {
        return view('auth.two-factor');
    }

    /**
     * Verification for Two Factor Auth
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        $user = $request->user();

        if ($user->usesTwoFactor('email')) {
            if (
                 (int) $request->one_time_password !== (int) $user->two_factor_code
                 || now()->gt($user->two_factor_expires_at)
            ) {
                abort(401, 'Invalid code.');
            }
        }

        if ($user->usesTwoFactor('authenticator')) {
            $authenticator = app(Authenticator::class)->boot($request);
            if (! $authenticator->isAuthenticated()) {
                abort(401, 'Invalid code.');
            }
        }

        $user->validateTwoFactorCode();

        return $this->verified();
    }

    /**
     * Verification response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verified()
    {
        return redirect(RouteServiceProvider::HOME)->withMessage('Two Factor verified.');
    }
}
