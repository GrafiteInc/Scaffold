<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Http\Forms\UserForm;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserUpdateRequest;

class SettingsController extends Controller
{
    /**
     * View current user's settings
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        $user = auth()->user();

        $form = app(UserForm::class)->edit($user);

        $deleteAccountForm = form()
            ->confirm(trans('general.user.delete_account'), 'confirmation')
            ->action('delete', 'user.destroy', 'Delete My Account', [
                'class' => 'btn btn-danger mt-4'
            ]);

        return view('user.settings')
            ->with('form', $form)
            ->with('deleteAccountForm', $deleteAccountForm);
    }

    /**
     * Update the user
     *
     * @param  UpdateAccountRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request)
    {
        try {
            $path = auth()->user()->avatar;

            if (!is_null($request->avatar)) {
                if (($request->file('avatar')->getSize() / 1024) > 10000) {
                    return back()->withErrors(['Avatar file is too big, must be below 10MB.']);
                }

                Storage::delete(auth()->user()->avatar);
                $path = Storage::putFile('public/avatars', $request->avatar, 'public');
            }

            auth()->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'dark_mode' => $request->filled('dark_mode') ?? false,
                'avatar' => $path,
                'allow_email_based_notifications' => $request->filled('allow_email_based_notifications') ?? false,
                'state' => $request->state,
                'country' => $request->country,
            ]);

            return back()->with('message', 'Settings updated successfully');
        } catch (Exception $e) {
            Log::error($e);
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Delete a user's avatar
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAvatar()
    {
        try {
            Storage::delete(auth()->user()->avatar);

            auth()->user()->update([
                'avatar' => null,
            ]);

            return back()->with('message', 'Avatar deleted successfully');
        } catch (Exception $e) {
            Log::error($e);
            return back()->withErrors($e->getMessage());
        }
    }
}
