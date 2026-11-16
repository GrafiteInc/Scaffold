<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureUserIsActive;
use Grafite\Auth\Foundation\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller implements HasMiddleware
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Set when the password was right but the account is disabled.
     */
    protected bool $accountDisabled = false;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
            new Middleware('auth', only: ['logout']),
        ];
    }

    /**
     * Make a note that the user logged in.
     *
     * @return void
     */
    public function authenticated()
    {
        activity('login');

        auth()->user()->deviceLogin();
    }

    /**
     * Only log in active users. The callback runs after the password checks
     * out, so a disabled user never fires Login or gets a session.
     *
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attemptWhen(
            $this->credentials($request),
            function ($user) {
                $this->accountDisabled = method_exists($user, 'isDisabled') && $user->isDisabled();

                return ! $this->accountDisabled;
            },
            $request->filled('remember')
        );
    }

    /**
     * Tell a disabled user why, rather than claiming bad credentials. Only
     * someone with the right password ever sees this.
     *
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [$this->accountDisabled ? EnsureUserIsActive::MESSAGE : trans('auth.failed')],
        ]);
    }
}
