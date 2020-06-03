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

Route::middleware('api')->namespace('Api')->name('api')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('me', 'UsersController@me')->name('.users.me');
        Route::put('update', 'UsersController@update')->name('.users.update');
        Route::delete('destroy', 'UsersController@destroy')->name('.users.destroy');
    });
});
