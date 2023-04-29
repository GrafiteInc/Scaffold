<?php

namespace App\Models\Concerns;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Notifications\TwoFactorNotification;

trait HasTwoFactor
{
    public function usesTwoFactor($type)
    {
        return $this->two_factor_platform === $type;
    }

    public function setTwoFactorCodeAttribute($value)
    {
        $this->attributes['two_factor_code'] = is_null($value) ? $value : encrypt($value);
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

    public function notConfirmedTwoFactor()
    {
        return is_null($this->two_factor_confirmed_at);
    }

    public function validateTwoFactorCode()
    {
        if (now()->gt($this->two_factor_expires_at)) {
            Auth::logout();
            Session::invalidate();
            Session::flush();

            abort(403, 'Expired Code');
        }

        $this->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'two_factor_confirmed_at' => now(),
        ]);
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
