<?php

namespace App\Http\Controllers\User;

use App\Models\Invite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\StandardEmail;
use Illuminate\Support\Facades\Notification;

class InvitesController extends Controller
{
    /**
     * Display a listing of invites.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $invites = $request->user()->invites;

        return view('user.invites.index')->with(compact('invites'));
    }

    /**
     * Accept an invite.
     *
     * @param \App\Models\Invite $invite
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Request $request, Invite $invite)
    {
        $relationship = $invite->relationship;

        $request->user()->$relationship()->attach($invite->model_id);

        if ($invite->delete()) {
            return redirect()->back()->withMessage('Invitation accepted');
        }

        return redirect()->back()->withErrors(['Unable to accept the inviation']);
    }

    /**
     * Reject an invite.
     *
     * @param \App\Models\Invite $invite
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Invite $invite)
    {
        $subject = 'Invitation Rejected';
        $message = "{$invite->email} politely rejected your inviation.";

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
