<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\InAppNotification;

class ApiTokenController extends Controller
{
    public function index(Request $request)
    {
        $tokens = $request->user()->tokens()->orderByDesc('created_at')->get();

        return view('user.api-tokens')->withTokens($tokens);
    }

    public function create(Request $request)
    {
        $token = $request->user()->createToken($request->name, $request->permissions ?? []);

        $notification = new InAppNotification('You have a new API token.');
        $notification->isImportant();

        activity("API token {$request->name} created.");

        $request->user()->notify($notification);

        return redirect()->back()->withToken($token->plainTextToken);
    }

    public function destroy(Request $request, $token)
    {
        $token = $request->user()->tokens()
            ->where('id', $token)->get()->first();

        activity("API token {$token->name} deleted.");

        $token->delete();

        return redirect()->back()->withMessage('Token deleted');
    }
}
