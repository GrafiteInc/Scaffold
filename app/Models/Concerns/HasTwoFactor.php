<?php

namespace App\Models\Concerns;

trait HasTwoFactor
{
    public function setTwoFactorCodeAttribute($value)
    {
        $this->attributes['two_factor_code'] = encrypt($value);
    }

    public function getTwoFactorCodeAttribute($value)
    {
        if (! is_null($value)) {
            return decrypt($value);
        }

        return null;
    }

    public function setTwoFactorCode()
    {
        if ($this->two_factor_platform === 'authenticator') {
            return $this->setTwoFactorForAuthenticator();
        }

        if ($this->two_factor_platform === 'email') {
            return $this->setTwoFactorForEmail();
        }

        return null;
    }

    public function hasTwoFactorCode()
    {
        return ! is_null($this->two_factor_code);
    }

    public function hasValidTwoFactorCode()
    {
        return ! is_null($this->two_factor_expires_at)
            && $this->two_factor_expires_at->gt(now());
    }

    public function validateTwoFactorCode()
    {
        $this->update([
            'two_factor_expires_at' => now()->addHours(config('auth.two_factor_valid_hours', 24)),
        ]);
    }

    public function setTwoFactorForEmail()
    {
        $code = mt_rand(100000, 999999);

        $this->update([
            'two_factor_code' => $code,
        ]);

        return (string) $code;
    }

    public function setTwoFactorForAuthenticator()
    {
        $google2fa = app('pragmarx.google2fa');
        $code = $google2fa->generateSecretKey();

        $this->update([
            'two_factor_code' => $code,
        ]);

        return $code;
    }
}
