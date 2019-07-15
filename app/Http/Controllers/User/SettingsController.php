<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Forms\UserForm;

class SettingsController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * View current user's settings
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        $user = auth()->user();

        $form = app(UserForm::class)->edit($user);

        return view('user.settings')->with('form', $form);
    }

    /**
     * Update the user
     *
     * @param  UpdateAccountRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request)
    {
        try {
            if ($this->service->update(auth()->user(), $request->all())) {
                return back()->with('message', 'Settings updated successfully');
            }

            return back()->withErrors(['Could not update user']);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
