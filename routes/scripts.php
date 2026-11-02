<?php

use Illuminate\Support\Facades\Route;

Route::get('service-worker.js', function () {
    $version = 'pwa-v-'.build_version();
    $response = view('scripts.serviceworker', [
        'version' => $version,
        'buildAssets' => build_assets(),
        'warmAssets' => pwa_warm_assets(),
        'cdnHosts' => config('laravelpwa.cdn_hosts', []),
    ])->render();

    return response($response)->header('Content-Type', 'application/javascript');
});
