<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sends a user flagged with `needs_password_reset` to the Security page until
 * they change their password. Web only: API and MCP tokens keep working.
 *
 * Applied (as `password.change`) to the verified/two-factor route group, so it
 * runs after 2FA and leaves logout, 2FA verify and "return switch" alone.
 */
class EnsurePasswordIsChanged
{
    public const MESSAGE = 'You need to change your password before continuing.';

    /**
     * Routes a flagged user can still reach.
     *
     * @var array<int, string>
     */
    protected array $except = [
        'user.security',
        'user.security.update',
        'user.logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user
            || ! method_exists($user, 'needsPasswordReset')
            || ! $user->needsPasswordReset()
            || $request->session()->has('original_user')
            || $request->routeIs(...$this->except)
        ) {
            return $next($request);
        }

        if ($request->is('ajax/*') || $request->expectsJson()) {
            return response()->json(['message' => self::MESSAGE], 403);
        }

        return redirect()->guest(route('user.security'));
    }
}
