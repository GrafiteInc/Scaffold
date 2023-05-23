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

        if (is_null($request->two_factor_platform)) {
            session()->forget('auth.two_factor_platform_temp');

            $request->user()->update([
                'two_factor_platform' => null,
            ]);
        }

        if (! is_null($request->two_factor_platform)) {
            activity('Changed Two Factor Authenticator.');

            session()->put('auth.two_factor_platform_temp', $request->two_factor_platform);

            if ($request->user()->usesTwoFactor('email')) {
                session()->forget('auth.two_factor_platform_temp');

                $request->user()->update([
                    'two_factor_platform' => 'email',
                ]);
            }

            if ($request->user()->usesTwoFactor('authenticator')) {
                $request->user()->setTwoFactorForAuthenticator();

                return true;
            }
        }

        return false;
    }
}
