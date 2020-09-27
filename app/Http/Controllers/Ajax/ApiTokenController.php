<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Notifications\InAppNotification;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function index(Request $request)
    {
        $tokens = $request->user()->tokens()->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'data' => [
                'tokens' => $tokens,
            ],
        ]);
    }

    public function create(Request $request)
    {
        $token = $request->user()->createToken($request->name, $request->permissions ?? []);

        $notification = new InAppNotification('You have a new API token.');
        $notification->isImportant();

        $request->user()->notify($notification);

        return response()->json([
            'data' => [
                'token' => $token->plainTextToken,
            ],
        ]);
    }

    public function destroy(Request $request, $token)
    {
        $request->user()->tokens()->where('id', $token)->delete();

        return response()->json([
            'message' => 'success',
        ]);
    }
}
