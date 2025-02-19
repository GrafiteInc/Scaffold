<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Display the Dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function get()
    {
        $images = collect(Storage::allFiles('public/uploads'))
            ->filter(function ($file) {
                return ! Str::startsWith(str_replace('public/uploads/', '', $file), '.');
            })->map(function ($file) {
                return str_replace('public', 'storage', $file);
            });

        $chart = app(\App\View\Charts\CanChart::class)->html();

        return view('dashboard', compact('images', 'chart'));
    }
}
