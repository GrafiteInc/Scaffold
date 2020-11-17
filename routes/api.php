<?php

use App\Http\Controllers\ApiDeviceLoginController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::post('device-login', [ApiDeviceLoginController::class, 'create'])->name('device.login');

Route::middleware('auth:sanctum')->name('api')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('me', [UsersController::class, 'me'])->name('.users.me');
        Route::put('update', [UsersController::class, 'update'])->name('.users.update');
        Route::delete('destroy', [UsersController::class, 'destroy'])->name('.users.destroy');
    });
});
