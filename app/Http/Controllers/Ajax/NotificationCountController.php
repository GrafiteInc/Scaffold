<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationCountController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'data' => $request->user()->unreadNotifications->count(),
        ]);
    }
}
