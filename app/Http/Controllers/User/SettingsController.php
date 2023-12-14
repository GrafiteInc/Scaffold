<?php

namespace App\Http\Controllers\User;

use App\Actions\UpdateUserAvatar;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * View current user's settings.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return view('user.settings')->with(compact('user'));
    }

    /**
     * Update the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request)
    {
        try {
            $request->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'avatar' => UpdateUserAvatar::handle($request) ?? $request->user()->avatar,
                'allow_email_based_notifications' => $request->filled('allow_email_based_notifications') ?? false,
            ]);

            activity('Settings updated.');

            return redirect()->route('user.settings')->withMessage('Settings updated successfully');
        } catch (Exception $e) {
            Log::error($e);

            return redirect()->route('user.settings')->withErrors($e->getMessage());
        }
    }

    /**
     * Delete a user's avatar.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAvatar(Request $request)
    {
        try {
            Storage::delete($request->user()->avatar);

            $request->user()->update([
                'avatar' => null,
            ]);

            return redirect()->back()->withMessage('Avatar deleted successfully');
        } catch (Exception $e) {
            Log::error($e);

            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
