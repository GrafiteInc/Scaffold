<?php

namespace App\Models\Concerns;

/**
 * A forced password change. The flag is written only through
 * UserService::requirePasswordReset()/clearPasswordReset(), never mass assigned,
 * and is cleared by the ClearPasswordResetRequirement listener once the
 * password changes.
 */
trait RequiresPasswordReset
{
    public function initializeRequiresPasswordReset(): void
    {
        $this->mergeCasts([
            'needs_password_reset' => 'boolean',
        ]);
    }

    public function needsPasswordReset(): bool
    {
        return (bool) $this->needs_password_reset;
    }
}
