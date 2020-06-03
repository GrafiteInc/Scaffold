<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\StandardEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class DestroyController extends Controller
{
    /**
     * Destroy a user account
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        Storage::delete($user->avatar);

        $subject = 'Account Deletion.';
        $message = 'Your account has been deleted.';

        Notification::route('mail', $user->email)
            ->notify(new StandardEmail($user->name, $subject, $message));

        $user->delete();

        Auth::logout();

        return redirect(route('home'))->with('message', 'Your account was deleted');
    }
}
