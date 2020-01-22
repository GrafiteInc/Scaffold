<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\UserResponse;
use App\Notifications\StandardEmail;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ApiUserUpdateRequest;
use Illuminate\Support\Facades\Notification;

class UsersController extends ApiController
{
    /**
     * Get the user data
     *
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        return response()->json([
            'data' => new UserResponse($this->user)
        ]);
    }

    /**
     * Update the user profile
     *
     * @param \App\Http\Requests\ApiUserUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUserUpdateRequest $request)
    {
        if ($this->user->update([
            'email' => $request->json('email'),
            'name' => $request->json('name'),
        ])) {
            return response()->json([
                'data' => new UserResponse($this->user),
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
