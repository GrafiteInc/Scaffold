<?php

use App\Http\Controllers\Ajax\CookiePolicyController;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Auth\RecoveryController;
use App\Http\Controllers\PagesController;
use Grafite\Auth\Facades\GrafiteAuth;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a given Closure or controller and enjoy the fresh air.
|
*/

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [PagesController::class, 'home'])->name('home');
Route::get('support', [PagesController::class, 'getSupport'])->name('support');
Route::get('terms-of-service', [PagesController::class, 'termsOfService'])->name('terms-of-service');
Route::get('privacy-policy', [PagesController::class, 'privacyPolicy'])->name('privacy-policy');

Route::post('accept-cookie-policy', [CookiePolicyController::class, 'accept'])->name('ajax.accept-cookie-policy');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::get('register/invite', [Auth\RegisterController::class, 'showRegistrationInviteForm'])
    ->name('register.with-invite');
Route::post('register/invite', [Auth\RegisterController::class, 'registerViaInvite'])
    ->name('register.invite');

Route::middleware([ProtectAgainstSpam::class])->group(function () {
    GrafiteAuth::routes([
        'login' => true,
        'logout' => true,
        'register' => config('general.registration_available', false),
        'reset' => true,
        'confirm' => true,
        'verify' => true,
    ], [
        'throttle:5,1',
    ]);

    Route::get('recovery', [RecoveryController::class, 'show'])
        ->name('recovery');
    Route::post('recovery', [RecoveryController::class, 'verify'])
        ->name('recovery.verify');
});
