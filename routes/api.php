<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'namespace' => 'Api',
    'as' => 'api'
], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login')->name('.auth.login');
        Route::post('logout', 'AuthController@logout')->name('.auth.logout');
        Route::post('refresh', 'AuthController@refresh')->name('.auth.refresh');
        Route::get('me', 'AuthController@me')->name('.auth.me');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::put('update', 'UsersController@update')->name('.users.update');
        Route::delete('destroy', 'UsersController@destroy')->name('.users.destroy');
    });

});
