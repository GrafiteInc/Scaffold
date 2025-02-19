<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutSessionsController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            auth()->logoutOtherDevices($request->password);

            auth()->user()->deviceLogout($request);

            return redirect()->route('user.security')->withMessage('Logged out of other devices.');
        } catch (\Throwable $th) {
            return redirect()->route('user.security')->withErrors($th->getMessage());
        }
    }
}
