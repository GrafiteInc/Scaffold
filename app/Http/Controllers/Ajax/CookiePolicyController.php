<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CookiePolicyController extends Controller
{
    public function accept(Request $request)
    {
        // TODO: log this in a database or somewhere.

        return response()->json([
            'message' => 'success',
        ]);
    }
}
