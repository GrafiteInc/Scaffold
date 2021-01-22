<?php

use Illuminate\Support\Facades\Route;

Route::get('serviceworker.js', function () {
    $version = 'pwa-v' . config('laravelpwa.version');
    // Here is where you could control version releases by
    // something content or database driven.
    $response = view('scripts.serviceworker')->withVersion($version)->render();

    return response($response)->header('Content-Type', 'application/javascript');
});
