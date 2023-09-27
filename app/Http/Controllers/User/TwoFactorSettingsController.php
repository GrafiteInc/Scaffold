<?php

namespace App\Http\Controllers\User;

use App\Actions\ProcessUserTwoFactorSettings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use PragmaRX\Google2FAQRCode\Google2FA;

class TwoFactorSettingsController extends Controller
{
    public function update(Request $request)
    {
        if (ProcessUserTwoFactorSettings::handle($request)) {
            return redirect()->route('user.security.two-factor')
                ->with([
                    'info' => 'Setup your authorization app.',
                    'show-codes' => true,
                ]);
        }

        return redirect()->back()->withMessage('Settings updated.');
    }

    /**
     * Setup the user 2Factor Auth
     *
     * @return \Illuminate\View\View
     */
    public function setup(Request $request)
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

    public function confirm(Request $request)
    {
        $user = $request->user();
        $authenticator = app(Authenticator::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            session()->forget('auth.two_factor_platform_temp');

            $user->validateTwoFactorCode();
            $request->user()->setAndSendTwoFactorRecoveryCodes();

            $user->update([
                'two_factor_platform' => 'authenticator',
                'two_factor_confirmed_at' => now(),
            ]);

            return redirect()->route('user.security')->withMessage('Two Factor confirmed');
        }

        return redirect()->route('user.security')->withWarning('Unable to confirm Two Factor.');
    }
}
