<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecoveryController extends Controller
{
    public function show(Request $request)
    {
        return view('auth.recovery');
    }

    /**
     * Verification for Two Factor Auth
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $codes = Str::of($user->two_factor_recovery_codes)->explode(',');

            if ($codes->contains($request->recovery_code)) {
                $user->update([
                    'two_factor_platform' => null,
                    'two_factor_code' => null,
                    'two_factor_expires_at' => null,
                    'two_factor_confirmed_at' => null,
                    'two_factor_recovery_codes' => null,
                ]);

                return $this->verified();
            }
        }

        abort(403, 'Invalid Code.');
    }

    /**
     * Verification response.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verified()
    {
        return redirect(RouteServiceProvider::HOME)->withMessage('Two Factor reset.');
    }
}
