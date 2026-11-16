<?php

namespace App\Models;

use App\Models\Concerns\DatabaseSearchable;
use App\View\Forms\WaitlistSignupForm;
use Grafite\Forms\Traits\HasForm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Someone who asked for early access from the waitlist landing page.
 *
 * @property string $email
 * @property string|null $name
 * @property array|null $meta
 * @property Carbon|null $invited_at
 * @property Carbon|null $registered_at
 */
class WaitlistSignup extends Model
{
    use DatabaseSearchable;
    use HasFactory;
    use HasForm;

    public $form = WaitlistSignupForm::class;

    protected $fillable = [
        'email',
        'name',
        'meta',
        'invited_at',
        'registered_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'invited_at' => 'datetime',
        'registered_at' => 'datetime',
    ];

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('invited_at')->whereNull('registered_at');
    }

    public function scopeInvited(Builder $query): Builder
    {
        return $query->whereNotNull('invited_at')->whereNull('registered_at');
    }

    public function scopeRegistered(Builder $query): Builder
    {
        return $query->whereNotNull('registered_at');
    }

    /**
     * Where the signup is in the funnel: pending, invited or registered.
     */
    public function getStatusAttribute(): string
    {
        return match (true) {
            ! is_null($this->registered_at) => 'registered',
            ! is_null($this->invited_at) => 'invited',
            default => 'pending',
        };
    }
}
