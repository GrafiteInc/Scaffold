<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class UsersController extends ApiController
{
    /**
     * Update the user profile
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($this->user->update([
            'email' => $request->json('email'),
            'name' => $request->json('name'),
        ])) {
            return response()->json([
                'data' => $this->user,
                'status' => 'Profile updated'
            ]);
        }

        return response()->json([
            'status' => 'Failed to update the profile.'
        ], 500);
    }

    /**
     * Delete the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        Storage::delete($this->user->avatar);

        $subject = 'Account Deletion.';
        $message = 'Your account has been deleted.';

        Notification::route('mail', $this->user->email)
            ->notify(new StandardEmail($this->user->name, $subject, $message));

        $this->user->delete();

        return response()->json([
            'status' => 'Profile deleted'
        ]);
    }
}
