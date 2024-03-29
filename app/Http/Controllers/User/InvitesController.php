<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Notifications\StandardEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class InvitesController extends Controller
{
    /**
     * Display a listing of invites.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $invites = $request->user()->invites;

        return view('user.invites.index')->with(compact('invites'));
    }

    /**
     * Accept an invite.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Request $request, Invite $invite)
    {
        $relationship = $invite->relationship;

        $request->user()->$relationship()->attach($invite->model_id);

        activity('Invite accepted.');

        if ($invite->delete()) {
            return redirect()->back()->withMessage('Invitation accepted');
        }

        return redirect()->back()->withErrors(['Unable to accept the inviation']);
    }

    /**
     * Reject an invite.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Invite $invite)
    {
        $subject = 'Invitation Rejected';
        $message = "{$invite->email} politely rejected your inviation.";

        activity($message);

        Notification::route('mail', $invite->from->email)
            ->notify(new StandardEmail(
                $invite->from->email,
                $subject,
                $message
            ));

        if ($invite->delete()) {
            return redirect()->back()->withMessage('Invitation rejected');
        }

        return redirect()->back()->withErrors(['Unable to reject the inviation']);
    }
}
