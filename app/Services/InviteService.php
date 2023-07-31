<?php

namespace App\Services;

use App\Models\Invite;
use App\Models\User;
use App\Notifications\InAppNotification;
use App\Notifications\UserInviteEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class InviteService
{
    /**
     * Create an invite.
     *
     * @param  mixed  $model
     * @param  string  $email
     * @param  string  $message
     * @return \App\Models\Invite
     */
    public function create($model, $email, $message)
    {
        $user = User::where('email', $email)->first();
        $token = Str::uuid();
        $from = auth()->user();

        if ($user) {
            $notification = new InAppNotification($message);
            $notification->isImportant();

            $user->notify($notification);
        }

        $this->deleteUnfulfilledInvites([
            'user_id' => auth()->id(),
            'relationship' => $model->relationship,
            'model_id' => $model->id,
            'email' => $email,
            'message' => $message,
        ]);

        $invite = Invite::create([
            'user_id' => auth()->id(),
            'relationship' => $model->relationship,
            'model_id' => $model->id,
            'email' => $email,
            'message' => $message,
            'token' => $token,
        ]);

        if (($user && $user->allow_email_based_notifications) || ! $user) {
            Notification::route('mail', $email)
                ->notify(new UserInviteEmail(
                    $email,
                    $from,
                    $message,
                    $token
                ));
        }

        return $invite;
    }

    /**
     * Delete all invites that match this since their
     * tokens will now be invalid.
     *
     * @param  array  $payload
     * @return void
     */
    public function deleteUnfulfilledInvites($payload)
    {
        Invite::where($payload)->delete();
    }

    /**
     * Find by the user activation token.
     *
     * @param  string  $token
     * @param  string  $email
     * @return bool
     */
    public function validateInvitation($token, $email)
    {
        $invite = Invite::where([
            'token' => $token,
            'email' => $email,
        ])->first();

        if ($invite) {
            if (is_null($invite->model_id)) {
                $invite->delete();
            }

            return true;
        }

        return false;
    }
}
