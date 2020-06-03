<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function count(Request $request)
    {
        return response()->json([
            'data' => $request->user()->unreadNotifications->count(),
        ]);
    }
}
