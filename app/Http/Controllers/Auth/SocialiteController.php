<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function __invoke($provider)
    {
        $user = Socialite::driver($provider)->user();

        dd($user);

        // $user->token

        // function ($provider) {
        //     $user = Socialite::driver($provider)->user();

        //     // $user->token

        //     // $githubUser = Socialite::driver('github')->user();

        //     // $user = User::updateOrCreate([
        //     //     'github_id' => $githubUser->id,
        //     // ], [
        //     //     'name' => $githubUser->name,
        //     //     'email' => $githubUser->email,
        //     //     'github_token' => $githubUser->token,
        //     //     'github_refresh_token' => $githubUser->refreshToken,
        //     // ]);

        //     // Auth::login($user);

        //     // return redirect('/dashboard');
        // });

    }
}
