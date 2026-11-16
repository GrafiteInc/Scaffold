<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ends access for a disabled user on whatever guard got them in: web session,
 * remember-me cookie, Sanctum token, or Passport (MCP).
 *
 * Runs in the web and api groups, sorted after the auth middleware so
 * `$request->user()` resolves on the route's guard. The MCP route has no group,
 * so routes/ai.php adds it as `active`.
 */
class EnsureUserIsActive
{
    public const MESSAGE = 'Your account has been disabled. Please contact support if you think this is a mistake.';

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! method_exists($user, 'isDisabled') || ! $user->isDisabled()) {
            return $next($request);
        }

        if ($request->hasSession() && Auth::guard('web')->check()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($request->is('api/*', 'ajax/*', 'mcp/*') || $request->expectsJson()) {
            return response()->json(['message' => self::MESSAGE], 403);
        }

        return redirect()->route('login')->withErrors(['email' => self::MESSAGE]);
    }
}
