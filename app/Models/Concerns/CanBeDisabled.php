<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Account-level access switch. The columns are written only through
 * UserService::disable()/enable(), never mass assigned.
 */
trait CanBeDisabled
{
    public function initializeCanBeDisabled(): void
    {
        $this->mergeCasts([
            'disabled_at' => 'datetime',
            'disabled_until' => 'datetime',
        ]);

        $this->makeHidden(['disabled_reason', 'disabled_by']);
    }

    /**
     * A timed disable lifts itself once `disabled_until` passes.
     */
    public function isDisabled(): bool
    {
        if (is_null($this->disabled_at)) {
            return false;
        }

        return is_null($this->disabled_until) || $this->disabled_until->isFuture();
    }

    public function scopeDisabled(Builder $query): Builder
    {
        return $query->whereNotNull('disabled_at')
            ->where(function (Builder $query) {
                $query->whereNull('disabled_until')->orWhere('disabled_until', '>', now());
            });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->whereNull('disabled_at')->orWhere('disabled_until', '<=', now());
        });
    }

    public function disabledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disabled_by');
    }
}
