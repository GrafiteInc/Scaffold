<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ApiUserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Notifications\StandardEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class UsersController extends ApiController
{
    /**
     * Get the user data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json([
            'data' => new UserResource($this->user),
        ]);
    }

    /**
     * Update the user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ApiUserUpdateRequest $request)
    {
        if (
            $this->user->update([
                'email' => $request->json('email'),
                'name' => $request->json('name'),
            ])
        ) {
            return response()->json([
                'data' => new UserResource($this->user),
                'status' => 'Profile updated',
            ]);
        }

        return response()->json([
            'status' => 'Failed to update the profile.',
        ], 500);
    }

    /**
     * Delete the user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        if ($this->user->avatar) {
            Storage::delete($this->user->avatar);
        }

        $subject = 'Account Deletion.';
        $message = 'Your account has been deleted.';

        Notification::route('mail', $this->user->email)
            ->notify(new StandardEmail($this->user->name, $subject, $message));

        $this->user->delete();

        return response()->json([
            'status' => 'Profile deleted',
        ]);
    }
}
