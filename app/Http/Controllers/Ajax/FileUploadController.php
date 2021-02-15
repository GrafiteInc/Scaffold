<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $paths = collect($request->file('file'))->map(function ($file) {
            return $file->store('public/uploads');
        });

        return response()->json([
            'data' => [
                'success' => true,
                'paths' => $paths,
            ],
        ]);
    }

    public function uploadImage(Request $request)
    {
        $path = $request->file('image')->store('public/uploads');

        return response()->json([
            'success' => true,
            'file' => [
                'url' => Storage::url($path),
            ],
        ]);
    }
}
