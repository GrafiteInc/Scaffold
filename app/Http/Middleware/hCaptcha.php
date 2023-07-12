<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;

class hCaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('h-captcha-response')) {
            if ($request->get('h-captcha-response')) {
                $response = Http::post('https://hcaptcha.com/siteverify', [
                    'secret' => config('services.hcaptcha.secret'),
                    'token' => $request->only('h-captcha-response'),
                ]);

                if ($response->successful()) {
                    return $next($request);
                }
            }

           return redirect()->back()->withErrors(['hCaptcha could not be confirmed.']);
        }

        return $next($request);
    }
}
