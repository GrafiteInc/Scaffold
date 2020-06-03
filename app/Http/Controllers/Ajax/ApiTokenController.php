<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Notifications\InAppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiTokenController extends Controller
{
    public function reset(Request $request)
    {
        $token = Str::random(80);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        $notification = new InAppNotification('You reset your API token.');
        $notification->isImportant();

        $request->user()->notify($notification);

        return response()->json([
            'data' => [
                'token' => $token,
            ],
        ]);
    }
}
