<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Forms\UserForm;
use App\Http\Requests\UserUpdateRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * View current user's settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings(Request $request)
    {
        $user = $request->user();

        $form = app(UserForm::class)->edit($user);

        $deleteAccountForm = form()
            ->confirm(trans('general.user.delete_account'), 'confirmation')
            ->action('delete', 'user.destroy', 'Delete My Account', [
                'class' => 'btn btn-danger mt-4',
            ]);

        return view('user.settings')
            ->with('form', $form)
            ->with('deleteAccountForm', $deleteAccountForm);
    }

    /**
     * Update the user.
     *
     * @param  UpdateAccountRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request)
    {
        try {
            $path = $request->user()->avatar;

            if (! is_null($request->avatar)) {
                if (($request->file('avatar')->getSize() / 1024) > 10000) {
                    return redirect()->back()->withErrors(['Avatar file is too big, must be below 10MB.']);
                }

                Storage::delete($request->user()->avatar);
                $path = Storage::putFile('public/avatars', $request->avatar, 'public');
            }

            $request->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'dark_mode' => $request->filled('dark_mode') ?? false,
                'avatar' => $path,
                'allow_email_based_notifications' => $request->filled('allow_email_based_notifications') ?? false,
                'billing_email' => $request->billing_email,
                'state' => $request->state,
                'country' => $request->country,
            ]);

            return redirect()->back()->with('message', 'Settings updated successfully');
        } catch (Exception $e) {
            Log::error($e);

            return redirect()->back()->withErrors($e->getMessage());
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

            return redirect()->back()->with('message', 'Avatar deleted successfully');
        } catch (Exception $e) {
            Log::error($e);

            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
