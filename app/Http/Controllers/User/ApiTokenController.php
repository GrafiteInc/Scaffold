<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiTokenController extends Controller
{
    /**
     * View current user's api tokens.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tokens = $request->user()->tokens()->orderBy('created_at', 'DESC')->get();

        return view('user.api-tokens')->withTokens($tokens);
    }
}
