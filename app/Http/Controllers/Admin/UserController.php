<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserUpdateRequest;
use App\Models\Invite;
use App\Models\User;
use App\Notifications\UserInviteEmail;
use App\View\Forms\AdminUserForm;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $invites = Invite::where([
            'model_id' => null,
        ])->get();

        $index = app(AdminUserForm::class)->index();

        return view('admin.users.index')
            ->with(compact('index', 'invites'));
    }

    /**
     * Search for a matching User.
     *
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = app(User::class)->search($request->search);

        $index = app(AdminUserForm::class)->index($query);

        $invites = Invite::where([
            'model_id' => null,
        ])->get();

        return view('admin.users.index')
            ->with(compact('index', 'invites'));
    }

    /**
     * Show the form for inviting a User.
     *
     * @return \Illuminate\View\View
     */
    public function getInvite()
    {
        return view('admin.users.invite');
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postInvite(Request $request)
    {
        $message = trans('general.user.invite');
        $token = Str::uuid();

        try {
            Invite::firstOrCreate([
                'user_id' => auth()->id(),
                'email' => $request->email,
            ], [
                'message' => $message,
                'token' => $token,
            ]);

            Notification::route('mail', $request->email)
                ->notify(new UserInviteEmail(
                    $request->email,
                    $request->user(),
                    $message,
                    $token
                ));

            return redirect()->back()->with('message', 'Invitation was sent');
        } catch (Throwable $th) {
            return redirect()->back()->withErrors(['Invitation was not sent']);
        }
    }

    /**
     * Switch to a different User.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchToUser(Request $request, User $user)
    {
        $request->session()->put('original_user', auth()->id());

        Auth::login($user);

        return redirect()->route('home')->withMessage('You switched users!');
    }

    /**
     * Switch back to your original user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchBack(Request $request)
    {
        if (! session('original_user')) {
            abort(401);
        }

        $user = User::find(session('original_user'));

        $request->session()->forget('original_user');

        Auth::login($user);

        return redirect()->route('home')->withMessage('You switched back!');
    }

    /**
     * Create a user
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random()),
                'timezone' => $request->timezone,
                'country' => $request->country,
            ]);

            // Assign the user to the role, then clear the roles cache
            $user->roles()->sync($request->role)->refreshCache();

            return redirect()->back()->with('message', 'Successfully created, but will need to reset password.');
        } catch (Exception $e) {
            Log::error($e);

            return redirect()->back()->with('errors', ['Failed to create']);
        }
    }

    /**
     * Show the form for editing the User.
     *
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $activities = $user->activities()->orderBy('created_at', 'DESC')
            ->limit(25)->get();

        return view('admin.users.edit')
            ->with(compact('activities', 'user'));
    }

    /**
     * Update the User in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminUserUpdateRequest $request, User $user)
    {
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $user->roles()->sync($request->role);

            return redirect()->back()->with('message', 'Successfully updated');
        } catch (Exception $e) {
            Log::error($e);

            return redirect()->back()->with('errors', ['Failed to update']);
        }
    }

    /**
     * Remove the User from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
