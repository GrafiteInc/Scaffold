<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Http\Request;
use App\Actions\UpdateUserAvatar;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Http\Requests\UserUpdateRequest;
use App\Actions\ProcessUserTwoFactorSettings;
use PragmaRX\Google2FALaravel\Support\Authenticator;

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
     * @param  UserUpdateRequest $request
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
                'billing_email' => $request->billing_email,
                'state' => $request->state,
                'country' => $request->country,
            ]);

            activity('Settings updated.');

            if (ProcessUserTwoFactorSettings::handle($request)) {
                return redirect()->route('user.settings.two-factor')
                    ->with([
                        'info' => 'Setup your authorization app.',
                        'show-codes' => true
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
        $google2fa = new Google2FA();

        $data = [
            'manual' => $request->user()->two_factor_code,
            'code' => $google2fa->setQrcodeService(
                new \PragmaRX\Google2FAQRCode\QRCode\Bacon(
                    new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
                )
            )->getQRCodeInline(
                config('app.name'),
                $request->user()->email,
                $request->user()->two_factor_code,
            ),
        ];

        // Show them the QR or manual code
        return view('user.authenticator', $data);
    }

    public function twoFactorConfirm(Request $request)
    {
        $user = $request->user();
        $authenticator = app(Authenticator::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            session()->forget('auth.two_factor_platform_temp');

            $user->validateTwoFactorCode();

            $user->update([
                'two_factor_platform' => 'authenticator',
                'two_factor_confirmed_at' => now(),
            ]);

            return redirect()->route('user.settings')->withMessage('Two Factor confirmed');
        }

        return redirect()->route('user.settings')->withWarning('Unable to confirm Two Factor.');
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
