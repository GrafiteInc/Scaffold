<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Forms\UserPasswordForm;
use Illuminate\Support\Facades\Hash;
use Collective\Auth\Foundation\ResetsPasswords;
use App\Http\Requests\UserPasswordUpdateRequest;

class ChangePasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectPath = '/user/settings/password';

    /**
     * User wants to change their password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $form = app(UserPasswordForm::class)->make();

        return view('user.password')->with(compact('form'));
    }

    /**
     * Change the user's password and return.
     *
     * @param  \App\Http\Requests\UserPasswordUpdateRequest $request
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
