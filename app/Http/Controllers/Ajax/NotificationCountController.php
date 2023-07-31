<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationCountController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'data' => $request->user()->unreadNotifications->count(),
        ]);
    }
}
