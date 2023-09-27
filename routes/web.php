<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Ajax\CookiePolicyController;
use App\Http\Controllers\Ajax\FileUploadController;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Auth\RecoveryController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ResendInviteController;
use App\Http\Controllers\RevokeInviteController;
use App\Http\Controllers\TeamMembersController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\User\ApiTokenController;
use App\Http\Controllers\User\BillingController;
use App\Http\Controllers\User\DestroyController;
use App\Http\Controllers\User\InvitesController;
use App\Http\Controllers\User\LogoutSessionsController;
use App\Http\Controllers\User\NotificationsController;
use App\Http\Controllers\User\SecurityController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\TwoFactorSettingsController;
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

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('verify/two-factor', [TwoFactorController::class, 'showForm'])
        ->name('verification.two-factor.code');
    Route::post('verify/two-factor', [TwoFactorController::class, 'verify'])
        ->name('verification.two-factor');

    Route::post('users/return-switch', [UserController::class, 'switchBack'])->name('users.return-switch');

    Route::middleware(['verified', 'two-factor'])->group(function () {
        Route::get('subscribed', function () {
            dd(request());
        });

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('dashboard', [DashboardController::class, 'get'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        Route::prefix('user')->group(function () {
            Route::get('settings', [SettingsController::class, 'index'])->name('user.settings');
            Route::get('security/two-factor', [TwoFactorSettingsController::class, 'setup'])->name('user.security.two-factor');
            Route::put('security/two-factor', [TwoFactorSettingsController::class, 'update'])->name('user.two-factor.update');
            Route::post('security/two-factor/confirm', [TwoFactorSettingsController::class, 'confirm'])->name('user.security.two-factor.confirm');

            Route::post('logout', LogoutSessionsController::class)->name('user.logout');

            Route::delete('destroy', [DestroyController::class, 'destroy'])->name('user.destroy');
            Route::put('settings', [SettingsController::class, 'update'])->name('user.update');
            Route::delete('avatar', [SettingsController::class, 'destroyAvatar'])->name('user.destroy.avatar');

            Route::get('security', [SecurityController::class, 'index'])
                ->name('user.security');
            Route::put('security', [SecurityController::class, 'update'])
                ->name('user.security.update');

            Route::get('api-tokens', [ApiTokenController::class, 'index'])->name('user.api-tokens');
            Route::delete('token/{token}/destroy', [ApiTokenController::class, 'destroy'])->name('user.destroy-token');
            Route::post('token', [ApiTokenController::class, 'create'])->name('user.create-token');

            // Route::prefix('billing')->group(function () {
            //     Route::middleware('has-subscription')->group(function () {
            //         Route::delete('cancel', [BillingController::class, 'cancel'])->name('user.billing.cancel');
            //     });
            // });

            Route::prefix('notifications')->group(function () {
                Route::get('/', [NotificationsController::class, 'index'])->name('user.notifications');
                Route::post('{uuid}/read', [NotificationsController::class, 'read'])->name('user.notifications.read');
                Route::delete('{uuid}/delete', [NotificationsController::class, 'delete'])->name('user.notifications.destroy');
                Route::delete('clear', [NotificationsController::class, 'deleteAll'])->name('user.notifications.clear');
            });

            Route::prefix('invites')->group(function () {
                Route::get('/', [InvitesController::class, 'index'])->name('user.invites');
                Route::post('{invite}/accept', [InvitesController::class, 'accept'])->name('user.invites.accept');
                Route::post('{invite}/reject', [InvitesController::class, 'reject'])->name('user.invites.reject');
            });

            Route::prefix('billing')->group(function () {
                Route::get('', [BillingController::class, 'index'])->name('user.billing');
                Route::post('update', [BillingController::class, 'update'])->name('user.billing.update');
                Route::post('subscribe', [BillingController::class, 'subscribe'])->name('user.billing.subscribe');
                Route::group(['gateway' => 'subscribed'], function () {
                    Route::post('swap', [BillingController::class, 'swap'])->name('user.billing.swap');
                    Route::post('coupon', [BillingController::class, 'coupon'])->name('user.billing.coupon');
                });
            });
        });

        Route::post('invites/{invite}/resend', ResendInviteController::class)->name('invite.resend');
        Route::post('invites/{invite}/revoke', RevokeInviteController::class)->name('invite.revoke');

        Route::prefix('teams')->group(function () {
            Route::get('/', [TeamsController::class, 'index'])->name('teams');
            Route::post('/', [TeamsController::class, 'store'])->name('teams.store');
            Route::get('create', [TeamsController::class, 'create'])->name('teams.create');
            Route::get('{team}/edit', [TeamsController::class, 'edit'])->name('teams.edit');
            Route::get('{team}/members', [TeamsController::class, 'members'])->name('teams.members');
            Route::delete('{team}/delete', [TeamsController::class, 'destroy'])->name('teams.destroy');
            Route::put('{team}/update', [TeamsController::class, 'update'])->name('teams.update');
            Route::delete('avatar', [TeamsController::class, 'destroyAvatar'])->name('team.destroy.avatar');

            Route::get('{team}', [TeamMembersController::class, 'show'])->name('teams.show');
            Route::post('{team}/leave', [TeamMembersController::class, 'leave'])->name('teams.leave');
            Route::post('{team}/invite', [TeamMembersController::class, 'inviteMember'])->name('teams.members.invite');
            Route::delete('{team}/remove/{member}', [TeamMembersController::class, 'removeMember'])->name('teams.members.remove');
            Route::get('{team}/edit/{member}', [TeamMembersController::class, 'editMember'])->name('teams.members.edit');
            Route::put('{team}/update/{member}', [TeamMembersController::class, 'updateMember'])->name('teams.members.update');
        });

        /*
        |--------------------------------------------------------------------------
        | Ajax calls (using normal auth)
        |--------------------------------------------------------------------------
        */

        Route::prefix('ajax')->group(function () {
            Route::post('file-upload', [FileUploadController::class, 'upload'])->name('ajax.files-upload');
            Route::post('image-upload', [FileUploadController::class, 'uploadImage'])->name('ajax.image-upload');
        });

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        Route::prefix('admin')->middleware(['roles:admin', 'password.confirm'])->group(function () {
            Route::get('dashboard', AdminDashboardController::class)->name('admin.dashboard');

            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */
            Route::get('users/search', [UserController::class, 'search'])
                ->middleware(['permissions:users'])
                ->name('admin.users.search');

            Route::get('users/invite', [UserController::class, 'getInvite'])
                ->middleware(['permissions:users.invite'])
                ->name('admin.users.invite');

            Route::post('users/invite', [UserController::class, 'postInvite'])
                ->middleware(['permissions:users.invite'])
                ->name('admin.users.send-invite');

            Route::post('users/switch/{user}', [UserController::class, 'switchToUser'])
                ->middleware(['permissions:users'])
                ->name('admin.users.switch');

            Route::resource('users', UserController::class, ['as' => 'admin', 'middleware' => ['permissions:users']]);
            Route::resource('roles', RoleController::class, ['as' => 'admin', 'middleware' => ['permissions:roles']]);
            Route::resource('announcements', AnnouncementController::class, ['as' => 'admin', 'middleware' => ['permissions:announcements']]);
        });
    });
});
