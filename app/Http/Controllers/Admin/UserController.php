<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Invite;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Forms\AdminUserForm;
use App\Http\Forms\InviteUserForm;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserInviteEmail;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\AdminUserUpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();
        $invites = Invite::where([
            'model_id' => null,
        ])->get();

        return view('admin.users.index')
            ->with(compact('users', 'invites'));
    }

    /**
     * Search for a matching User.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $users = app(User::class)
            ->search($request->search)
            ->get();

        $invites = Invite::where([
            'model_id' => null,
        ])->get();

        return view('admin.users.index')
            ->with('users', $users)
            ->with('invites', $invites);
    }

    /**
     * Show the form for inviting a User.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvite()
    {
        $form = app(InviteUserForm::class)->make();

        return view('admin.users.invite')
            ->with('form', $form);
    }

    /**
     * Show the form for inviting a customer.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postInvite(Request $request)
    {
        $message = trans('general.user.invite');
        $token = Str::uuid();

        $invite = Invite::firstOrCreate([
            'user_id' => auth()->id(),
            'email' => $request->email,
            'message' => $message,
            'token' => $token,
        ]);

        if ($invite) {
            Notification::route('mail', $request->email)
                ->notify(new UserInviteEmail(
                    $request->email,
                    auth()->user(),
                    $message,
                    $token
                ));

            return back()->with('message', 'Invitation was sent');
        }

        return back()->withErrors(['Invitation was not sent']);
    }

    /**
     * Switch to a different User
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchToUser(User $user)
    {
        session()->put('original_user', auth()->id());

        Auth::login($user);

        return redirect(route('home'))->withMessage('You switched users!');
    }

    /**
     * Switch back to your original user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchBack()
    {
        if (! session('original_user')) {
            abort(401);
        }

        $user = User::find(session('original_user'));

        session()->forget('original_user');

        Auth::login($user);

        return redirect(route('home'))->withMessage('You switched back!');
    }

    /**
     * Show the form for editing the User.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $form = app(AdminUserForm::class)->edit($user);
        $activities = $user->activities()->limit(25)->get()->reverse();

        return view('admin.users.edit')
            ->with('activities', $activities)
            ->with('form', $form)
            ->with('user', $user);
    }

    /**
     * Update the User in storage.
     *
     * @param  \App\Http\Requests\AdminUserUpdateRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminUserUpdateRequest $request, User $user)
    {
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $user->roles()->sync($request->roles);

            return back()->with('message', 'Successfully updated');
        } catch (Exception $e) {
            Log::error($e);
            return back()->with('errors', ['Failed to update']);
        }
    }

    /**
     * Remove the User from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect(route('admin.users.index'));
    }
}
