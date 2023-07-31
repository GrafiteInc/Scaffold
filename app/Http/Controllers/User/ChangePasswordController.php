<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPasswordUpdateRequest;
use Grafite\Auth\Foundation\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectPath = '/user/settings/password';

    /**
     * User wants to change their password.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('user.password');
    }

    /**
     * Change the user's password and return.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserPasswordUpdateRequest $request)
    {
        $password = $request->new_password;

        if (Hash::check($request->old_password, $request->user()->password)) {
            $this->resetPassword($request->user(), $password);

            activity('Password updated');

            return redirect()->route('user.settings')
                ->withMessage('Password updated successfully');
        }

        return redirect()->back()->withErrors(['Password could not be updated']);
    }
}
