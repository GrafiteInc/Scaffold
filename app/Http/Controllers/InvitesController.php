<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invite;
use App\Notifications\StandardEmail;
use App\Notifications\UserInviteEmail;
use Illuminate\Support\Facades\Notification;

class InvitesController extends Controller
{
    /**
     * Delete the invitation
     *
     * @param  \App\Models\Invite  $appModelsInvite
     * @return \Illuminate\Http\Response
     */
    public function revoke(Invite $invite)
    {
        try {
            $email = $invite->email;
            $subject = 'Invitation Deleted';
            $message = "Your invitation from {$invite->from->name} has been removed.";

            Notification::route('mail', $invite->email)
                ->notify(new StandardEmail($email, $subject, $message));

            $invite->delete();

            return back()->with('message', 'Invitation was revoked');
        } catch (Exception $e) {
            return back()->withErrors(['Invitation was unable to be revoked']);
        }
    }

    /**
     * Resend the invitation
     *
     * @param  \App\Models\Invite  $appModelsInvite
     * @return \Illuminate\Http\Response
     */
    public function resend(Invite $invite)
    {
        try {
            Notification::route('mail', $invite->email)
                ->notify(new UserInviteEmail(
                    $invite->email,
                    $invite->from,
                    $invite->message,
                    $invite->token
                ));

            return back()->with('message', 'Invitation was resent');
        } catch (Exception $e) {
            return back()->withErrors(['Invitation was unable to be resent']);
        }
    }
}
