<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\InAppNotification;

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
