<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Forms\ImageUploadForm;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Display the Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $images = collect(Storage::allFiles('public/uploads'))
            ->filter(function ($file) {
                return ! Str::startsWith(str_replace('public/uploads/', '', $file), '.');
            })->map(function ($file) {
                return str_replace('public', 'storage', $file);
            });

        $form = app(ImageUploadForm::class)->make();

        return view('dashboard', compact('images', 'form'));
    }
}
