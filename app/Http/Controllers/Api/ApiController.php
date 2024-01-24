<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function user()
    {
        $user = auth('sanctum')->user();

        if (is_null($user) && ! app()->runningInConsole()) {
            abort(401, 'Unauthorized.');
        }

        return $user;
    }
}
