<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    public function count(Request $request)
    {
        return response()->json([
            'data' => $request->user()->unreadNotifications->count(),
        ]);
    }
}
