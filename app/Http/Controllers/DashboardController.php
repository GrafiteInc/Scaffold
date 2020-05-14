<?php

namespace App\Http\Controllers;

use App\Http\Forms\ImageUploadForm;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Display the Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $images = Storage::allFiles('public/uploads');

        foreach ($images as &$image) {
            $image = str_replace('public', 'storage', $image);
        }

        $form = app(ImageUploadForm::class)->make();

        return view('dashboard.main', compact('images', 'form'));
    }
}
