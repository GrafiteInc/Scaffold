<?php

namespace App\Actions;

class ProcessUserTwoFactorSettings
{
    public static function handle($request)
    {
        if (! is_null($request->user()->two_factor_platform)) {
            activity('Enabled Two Factor Authenticator.');

            $request->user()->setTwoFactorCode();

            if ($request->user()->two_factor_platform === 'email') {
                $request->user()->validateTwoFactorCode();
            }

            if ($request->user()->two_factor_platform === 'authenticator') {
                $google2fa = app('pragmarx.google2fa');
                // log in the user automatically
                $google2fa->login();

                return true;
            }
        } else {
            $request->user()->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);
        }

        return false;
    }
}
