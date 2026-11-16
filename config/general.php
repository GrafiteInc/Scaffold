<?php

/**
 * App custom configs
 */

// "a.com, B.com" -> ['a.com', 'b.com']
$domains = fn (?string $value): array => array_values(array_filter(
    array_map(fn (string $domain) => strtolower(trim($domain)), explode(',', (string) $value))
));

return [
    'email' => [
        'image_for_header' => true,
        'image' => 'https://avatars.githubusercontent.com/u/37278177?s=400&u=1d34c1aeea07e5f4fe79654790581b7a47e80ffb&v=4',
    ],

    'registration_available' => env('REGISTRATION', false),

    /*
     * When on, the home page is the early-access landing page with a waitlist signup.
     * The page is always reachable at /waitlist; signups are managed in Admin > Waitlist.
     */
    'waitlist_enabled' => env('WAITLIST', false),

    /*
     * Comma-separated domains, enforced by App\Rules\RegistrableEmail on sign-up and
     * self-service email changes. Subdomains match too. When the allow list is set,
     * only those domains may register. Invite registration skips both lists.
     */
    'registration_allowed_domains' => $domains(env('REGISTRATION_ALLOWED_DOMAINS')),

    'registration_blocked_domains' => $domains(env('REGISTRATION_BLOCKED_DOMAINS')),

    /*
     * Temporarily disable a signed-in user after `strikes` separate throttle
     * episodes within `window` minutes, for `duration` minutes. Login lockouts
     * never count (they're keyed by a typed email, so they could target anyone).
     * See App\Listeners\DisableUserOnRepeatedRateLimiting.
     */
    'auto_disable' => [
        'enabled' => (bool) env('AUTO_DISABLE_ENABLED', false),
        'strikes' => (int) env('AUTO_DISABLE_STRIKES', 5),
        'window' => (int) env('AUTO_DISABLE_WINDOW', 60),
        'duration' => (int) env('AUTO_DISABLE_DURATION', 1440),
        'exempt_roles' => ['admin'],
    ],
];
