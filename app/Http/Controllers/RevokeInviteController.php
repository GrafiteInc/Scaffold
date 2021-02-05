<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invite;
use App\Notifications\StandardEmail;
use Illuminate\Support\Facades\Notification;

class RevokeInviteController extends Controller
{
    /**
     * Delete the invitation.
     *
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Invite $invite)
    {
        try {
            $email = $invite->email;
            $subject = 'Invitation Deleted';
            $message = "Your invitation from {$invite->from->name} has been removed.";

            Notification::route('mail', $invite->email)
                ->notify(new StandardEmail($email, $subject, $message));

            $invite->delete();

            return redirect()->back()->withMessage('Invitation was revoked');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['Invitation was unable to be revoked']);
        }
    }
}
