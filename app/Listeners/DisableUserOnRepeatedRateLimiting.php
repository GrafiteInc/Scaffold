<?php

namespace App\Listeners;

use App\Events\RateLimitExceeded;
use App\Services\UserService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class DisableUserOnRepeatedRateLimiting
{
    public function __construct(
        protected UserService $service,
    ) {}

    /**
     * Temporarily disable a signed-in user who keeps hitting rate limits.
     *
     * Off unless general.auto_disable.enabled is set. A "strike" is one throttle
     * episode, not one 429: the requests rejected inside the same window count once.
     *
     * @return void
     */
    public function handle(RateLimitExceeded $event)
    {
        $config = config('general.auto_disable');
        $user = $event->user;

        if (! ($config['enabled'] ?? false) || ! $user || $user->isDisabled()) {
            return;
        }

        foreach ($config['exempt_roles'] ?? [] as $role) {
            if ($user->hasRole($role)) {
                return;
            }
        }

        $episode = "rate-limit-strike:{$user->getKey()}:".sha1($event->key);

        if (! Cache::add($episode, true, max($event->retryAfter, 1))) {
            return;
        }

        $strikesKey = "auto-disable:{$user->getKey()}";

        RateLimiter::hit($strikesKey, (int) $config['window'] * 60);

        if (RateLimiter::attempts($strikesKey) < (int) $config['strikes']) {
            return;
        }

        RateLimiter::clear($strikesKey);

        $this->service->disable(
            $user,
            'Automatically disabled after repeated rate limiting.',
            now()->addMinutes((int) $config['duration']),
        );
    }
}
