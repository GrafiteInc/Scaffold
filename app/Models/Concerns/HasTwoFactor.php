<?php

namespace App\Models\Concerns;

use App\Notifications\TwoFactorNotification;
use App\Notifications\TwoFactorRecoveryNotification;
use PragmaRX\Recovery\Recovery;

trait HasTwoFactor
{
    public function usesTwoFactor($type)
    {
        if (session('auth.two_factor_platform_temp')) {
            return session('auth.two_factor_platform_temp') === $type;
        }

        return $this->two_factor_platform === $type;
    }

    public function setTwoFactorCodeAttribute($value)
    {
        $this->attributes['two_factor_code'] = is_null($value) ? $value : encrypt($value);
    }

    public function setTwoFactorRecoveryCodesAttribute($value)
    {
        $this->attributes['two_factor_recovery_codes'] = is_null($value) ? $value : encrypt($value);
    }

    public function getTwoFactorCodeAttribute($value)
    {
        if (! is_null($value)) {
            return decrypt($value);
        }

        return null;
    }

    public function getTwoFactorRecoveryCodesAttribute($value)
    {
        if (! is_null($value)) {
            return decrypt($value);
        }

        return null;
    }

    public function setTwoFactorCode()
    {
        if ($this->usesTwoFactor('authenticator')) {
            return $this->setTwoFactorForAuthenticator();
        }

        if ($this->usesTwoFactor('email')) {
            return $this->setTwoFactorForEmail();
        }

        return null;
    }

    public function hasTwoFactorCode()
    {
        return ! is_null($this->two_factor_code);
    }

    public function notConfirmedTwoFactor()
    {
        return ! session('auth.two_factor_confirmed', false);
    }

    public function hasConfirmedAuthenticator()
    {
        return ! is_null($this->two_factor_confirmed_at);
    }

    public function validateTwoFactorCode()
    {
        $this->update([
            'two_factor_expires_at' => null,
        ]);

        session()->put('auth.two_factor_confirmed', true);
    }

    public function setAndSendTwoFactorForEmail()
    {
        $this->setTwoFactorForEmail();
        $this->notify(new TwoFactorNotification());
    }

    public function setTwoFactorForEmail()
    {
        $code = mt_rand(100000, 999999);

        $this->update([
            'two_factor_expires_at' => now()->addMinutes(config('auth.two_factor_valid_hours', 10)),
            'two_factor_code' => $code,
        ]);

        return (string) $code;
    }

    public function getTwoFactorPlatformAttribute($value)
    {
        if (session('auth.two_factor_platform_temp')) {
            return session('auth.two_factor_platform_temp');
        }

        return $value;
    }

    public function setTwoFactorForAuthenticator()
    {
        $google2fa = app('pragmarx.google2fa');
        $code = $google2fa->generateSecretKey();

        $recovery = new Recovery();
        $recoveryCodes = $recovery->toArray();

        $this->update([
            'two_factor_code' => $code,
            'two_factor_recovery_codes' => implode(',', $recoveryCodes),
        ]);

        $this->notify(new TwoFactorRecoveryNotification($recoveryCodes));

        return $code;
    }
}
