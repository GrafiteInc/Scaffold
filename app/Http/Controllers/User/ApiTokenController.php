<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
