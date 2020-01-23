<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'PagesController@home')->name('home');
Route::get('terms-of-service', 'PagesController@termsOfService')->name('terms-of-service');
Route::get('privacy-policy', 'PagesController@privacyPolicy')->name('privacy-policy');
Route::get('contact', 'PagesController@getContact')->name('contact');

Route::post('accept-cookie-policy', 'Ajax\CookiePolicyController@accept')->name('ajax.accept-cookie-policy');

Route::post(
    'stripe/webhook',
    '\App\Http\Controllers\WebhookController@handleWebhook'
);

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::get('register/invite', 'Auth\RegisterController@showRegistrationInviteForm');
Route::post('register/invite', 'Auth\RegisterController@registerViaInvite');

Auth::routes([
    'verify' => true
]);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'verified', 'activity']], function () {

    Route::post('users/return-switch', 'Admin\UserController@switchBack')->name('users.return-switch');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('dashboard', 'DashboardController@get')->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
        Route::get('settings', 'SettingsController@settings')->name('user.settings');
        Route::delete('destroy', 'DestroyController@destroy')->name('user.destroy');
        Route::put('settings', 'SettingsController@update')->name('user.update');
        Route::delete('avatar', 'SettingsController@destroyAvatar')->name('user.destroy.avatar');

        Route::get('security', 'SecurityController@get')->name('user.security');
        Route::put('security', 'SecurityController@update')->name('user.security.update');

        Route::group(['prefix' => 'billing'], function () {
            Route::get('/', 'BillingController@settings')->name('user.billing');

            Route::group(['middleware' => 'has-subscription'], function () {
                Route::post('update', 'BillingController@update')->name('user.billing.update');
                Route::post('update', 'BillingController@update')->name('user.billing.update');
                Route::post('update', 'BillingController@update')->name('user.billing.update');
                Route::delete('cancel', 'BillingController@cancel')->name('user.billing.cancel');
            });
        });

        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/', 'NotificationsController@index')->name('user.notifications');
            Route::post('{uuid}/read', 'NotificationsController@read')->name('user.notifications.read');
            Route::delete('{uuid}/delete', 'NotificationsController@delete')->name('user.notifications.destroy');
            Route::delete('clear', 'NotificationsController@deleteAll')->name('user.notifications.clear');
        });

        Route::group(['prefix' => 'teams'], function () {
            Route::get('/', 'TeamsController@index')->name('user.teams');
            Route::post('/', 'TeamsController@store')->name('user.teams.store');
            Route::get('create', 'TeamsController@create')->name('user.teams.create');
            Route::get('{team}/edit', 'TeamsController@edit')->name('user.teams.edit');
            Route::get('{team}', 'TeamsController@show')->name('user.teams.show');
            Route::delete('{team}/delete', 'TeamsController@destroy')->name('user.teams.destroy');
            Route::put('{team}/update', 'TeamsController@update')->name('user.teams.update');
            Route::post('{team}/invite', 'TeamsController@invite')->name('user.teams.invite');
            Route::post('{team}/leave', 'TeamsController@leave')->name('user.teams.leave');
            Route::delete('{team}/remove/{member}', 'TeamsController@remove')->name('user.teams.remove');
        });

        Route::group(['prefix' => 'invites'], function () {
            Route::get('/', 'InvitesController@index')->name('user.invites');
            Route::post('{invite}/accept', 'InvitesController@accept')->name('user.invites.accept');
            Route::post('{invite}/reject', 'InvitesController@reject')->name('user.invites.reject');
        });

        Route::group(['prefix' => 'billing'], function () {
            Route::get('subscribe', 'BillingController@subscribe')->name('user.billing');
            Route::get('renew', 'BillingController@renewSubscription')->name('user.billing.renew');
            Route::get('details', 'BillingController@getSubscription')->name('user.billing.details');
            Route::group(['gateway' => 'subscribed'], function () {
                Route::get('payment-method', 'BillingController@paymentMethod')->name('user.billing.payment-method');
                Route::get('change-plan', 'BillingController@getChangePlan')->name('user.billing.change-plan');
                Route::post('swap-plan', 'BillingController@swapPlan')->name('user.billing.swap-plan');
                Route::post('cancellation', 'BillingController@cancelSubscription')->name('user.subscription.cancel');
                Route::get('invoices', 'BillingController@getInvoices')->name('user.billing.invoices');
                Route::get('invoice/{id}', 'BillingController@getInvoiceById')->name('user.billing.invoice');
                Route::get('coupon', 'BillingController@getCoupon')->name('user.billing.coupons');
                Route::post('apply-coupon', 'BillingController@applyCoupon')->name('user.billing.apply-coupon');
            });
        });

    });

    Route::post('invites/{invite}/resend', 'InvitesController@resend')->name('invite.resend');
    Route::post('invites/{invite}/revoke', 'InvitesController@revoke')->name('invite.revoke');

    /*
    |--------------------------------------------------------------------------
    | Ajax calls (using normal auth)
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {
        Route::post('token', 'ApiTokenController@reset')->name('ajax.reset-token');
        Route::get('notifications-count', 'NotificationsController@count')->name('ajax.notifications-count');

        Route::post('subscribe', 'BillingController@createSubscription')
            ->name('ajax.billing.subscription.create');
        Route::post('payment-method', 'BillingController@updatePaymentMethod')
            ->name('ajax.billing.subscription.payment-method');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'permissions:roles|users'], function () {

        Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        Route::resource('users', 'UserController', ['except' => ['create', 'show'], 'as' => 'admin']);

        Route::post('users/search', 'UserController@search')->name('admin.users.search');

        Route::get('users/invite', 'UserController@getInvite')->name('admin.users.invite');
        Route::post('users/invite', 'UserController@postInvite')->name('admin.users.send-invite');

        Route::post('users/switch/{user}', 'UserController@switchToUser')->name('admin.users.switch');

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        Route::resource('roles', 'RoleController', ['except' => ['show'], 'as' => 'admin']);
    });
});
