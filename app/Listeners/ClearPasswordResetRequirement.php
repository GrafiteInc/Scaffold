<?php

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset;

class ClearPasswordResetRequirement
{
    /**
     * Both the Security page and the forgot-password flow change the password
     * through ResetsPasswords::resetPassword(), which fires PasswordReset.
     */
    public function handle(PasswordReset $event): void
    {
        $user = $event->user;

        if (! method_exists($user, 'needsPasswordReset') || ! $user->needsPasswordReset()) {
            return;
        }

        $user->forceFill(['needs_password_reset' => false])->save();
    }
}
