<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class TwoFactorController extends Controller
{
    public function showVerificationForm(Request $request)
    {
        return view('auth.two-factor');
    }

    /**
     * Verification for Two Factor Auth
     *
     * @param Request $request
     * @param string $code
     * @return void
     */
    public function twoFactorVerification(Request $request)
    {
        if ($request->user()->two_factor_platform === 'email') {
            if ((int) $request->one_time_password === $request->user()->two_factor_code) {
                $request->user()->validateTwoFactorCode();

                return $this->verified();
            }

            abort(401, 'Invalid code.');
        }

        $authenticator = app(Authenticator::class)->boot($request);

        if (! $authenticator->isAuthenticated()) {
            abort(401, 'Invalid code.');
        }

        return $this->verified();
    }

    /**
     * Verification response.
     *
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function verified()
    {
        return redirect(RouteServiceProvider::HOME)->withMessage('Login verified.');
    }
}
