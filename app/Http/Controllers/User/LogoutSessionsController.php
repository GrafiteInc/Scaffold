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

            return redirect()->route('user.settings')->withMessage('Logged out of other devices.');
        } catch (\Throwable $th) {
            return redirect()->route('user.settings')->withErrors($th->getMessage());
        }
    }
}
