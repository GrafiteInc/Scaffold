<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * A request hit a rate limit: a `throttle:*` route (source `route`) or the login
 * lockout (source `login`).
 *
 * Login lockouts never carry a user. They are keyed by the submitted email, which
 * anyone can type, so acting on the account would let an attacker disable a victim.
 */
class RateLimitExceeded
{
    use Dispatchable;

    public const SOURCE_ROUTE = 'route';

    public const SOURCE_LOGIN = 'login';

    public function __construct(
        public ?User $user,
        public string $source,
        public string $key,
        public int $maxAttempts,
        public int $retryAfter,
        public ?string $ip = null,
        public ?string $path = null,
        public ?string $email = null,
    ) {}
}
