<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutSessionsController extends Controller
{
    public function __invoke(Request $request)
    {
        auth()->logoutOtherDevices($request->password);

        return redirect()->route('user.settings')->withMessage('Logged out of other devices.');
    }
}
