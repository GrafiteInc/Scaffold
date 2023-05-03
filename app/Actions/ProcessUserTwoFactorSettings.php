<?php

namespace App\Actions;

class ProcessUserTwoFactorSettings
{
    public static function handle($request)
    {
        $request->user()->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'two_factor_confirmed_at' => null,
            'two_factor_recovery_codes' => null,
        ]);

        $request->session()->forget('auth.two_factor_confirmed');

        if (! is_null($request->user()->two_factor_platform)) {
            activity('Enabled Two Factor Authenticator.');

            $request->user()->setTwoFactorCode();

            if ($request->user()->usesTwoFactor('authenticator')) {
                $google2fa = app('pragmarx.google2fa');
                // log in the user automatically
                $google2fa->login();

                return true;
            }
        }

        return false;
    }
}
