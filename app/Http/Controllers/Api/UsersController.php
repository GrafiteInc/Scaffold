<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ApiUserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Notifications\StandardEmail;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class UsersController extends ApiController
{
    public function __construct(public UserService $service) {}

    /**
     * Get the user data.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json([
            'data' => new UserResource($this->user()),
        ]);
    }

    /**
     * Update the user profile.
     *
     * @return JsonResponse
     */
    public function update(ApiUserUpdateRequest $request)
    {
        try {
            $user = $this->service->update($this->user(), [
                'email' => $request->json('email'),
                'name' => $request->json('name'),
            ]);

            return response()->json([
                'data' => new UserResource($user),
                'status' => 'Profile updated',
            ]);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 'Failed to update the profile.',
            ], 500);
        }
    }

    /**
     * Delete the user profile.
     *
     * Completely deletes the user account.
     * Will output an email notification of the deleted account.
     *
     * @return JsonResponse
     */
    public function destroy()
    {
        if ($this->user()->avatar) {
            Storage::delete($this->user()->avatar);
        }

        $subject = 'Account Deletion.';
        $message = 'Your account has been deleted.';

        Notification::route('mail', $this->user()->email)
            ->notify(new StandardEmail($this->user()->name, $subject, $message));

        $this->user()->delete();

        return response()->json([
            'status' => 'Profile deleted',
        ]);
    }
}
