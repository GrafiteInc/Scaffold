<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Http\Request;
use App\Http\Forms\LogoutForm;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserUpdateRequest;

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

        $logoutForm = app(LogoutForm::class)->make();

        $deleteAccountForm = form()
            ->action('delete', 'user.destroy', 'Please delete my account', [
                'class' => 'btn btn-outline-primary float-end',
            ])
            ->confirmAsModal(trans('general.user.delete_account'), 'Delete My Account', 'btn btn-block btn-danger mb-6');

        return view('user.settings')->with(compact('deleteAccountForm', 'logoutForm'));
    }

    /**
     * Update the user.
     *
     * @param  UserUpdateRequest $request
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
                'avatar' => $path,
                'allow_email_based_notifications' => $request->filled('allow_email_based_notifications') ?? false,
                'billing_email' => $request->billing_email,
                'state' => $request->state,
                'country' => $request->country,
                'two_factor_platform' => $request->two_factor_platform,
            ]);

            activity('Settings updated.');

            if (! is_null($request->user()->two_factor_platform)) {
                activity('Enabled Two Factor Authenticator.');

                $request->user()->setTwoFactorCode();

                if ($request->user()->two_factor_platform === 'email') {
                    $request->user()->validateTwoFactorCode();
                }

                if ($request->user()->two_factor_platform === 'authenticator') {
                    $google2fa = app('pragmarx.google2fa');
                    // log in the user automatically
                    $google2fa->login();

                    return redirect()->route('user.settings.two-factor')->withInfo('Setup your authorization app.');
                }
            } else {
                $request->user()->update([
                    'two_factor_code' => null,
                    'two_factor_expires_at' => null,
                ]);
            }

            return redirect()->route('user.settings')->withMessage('Settings updated successfully');
        } catch (Exception $e) {
            Log::error($e);

            return redirect()->route('user.settings')->withErrors($e->getMessage());
        }
    }

    /**
     * Setup the user 2Factor Auth
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function twoFactorSetup(Request $request)
    {
        $google2fa = app('pragmarx.google2fa');
        // Show them the QR or manual code
        return view('user.authenticator', [
            'manual' => $request->user()->two_factor_code,
            'code' => $google2fa->getQRCodeInline(
                config('app.name'),
                $request->user()->email,
                $request->user()->two_factor_code,
            ),
        ]);
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
