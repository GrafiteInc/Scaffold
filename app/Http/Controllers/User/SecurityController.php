<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Forms\UserSecurityForm;
use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{
    use ResetsPasswords;

    protected $redirectPath = '/user/security';

    /**
     * User wants to change their password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $form = app(UserSecurityForm::class)->make();

        return view('user.password')->with(compact('form'));
    }

    /**
     * Change the user's password and return.
     *
     * @param  \App\Http\Requests\PasswordUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PasswordUpdateRequest $request)
    {
        $password = $request->new_password;

        if (Hash::check($request->old_password, $request->user()->password)) {
            $this->resetPassword($request->user(), $password);

            return redirect()->to('user/settings')
                ->withMessage('Password updated successfully');
        }

        return redirect()->back()->withErrors(['Password could not be updated']);
    }
}
