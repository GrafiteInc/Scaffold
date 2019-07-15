<?php

namespace App\Http\Controllers\User;

use Hash;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Forms\UserSecurityForm;

class SecurityController extends Controller
{
    use ResetsPasswords;

    protected $redirectPath = '/user/security';

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * User wants to change their password
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $form = app(UserSecurityForm::class)->make();

        return view('user.password')
            ->with('form', $form);
    }

    /**
     * Change the user's password and return
     *
     * @param  PasswordUpdateRequest $request
     * @return Response
     */
    public function update(PasswordUpdateRequest $request)
    {
        $password = $request->new_password;

        if (Hash::check($request->old_password, Auth::user()->password)) {
            $this->resetPassword(Auth::user(), $password);
            return redirect('user/settings')
                ->with('message', 'Password updated successfully');
        }

        return back()->withErrors(['Password could not be updated']);
    }
}
