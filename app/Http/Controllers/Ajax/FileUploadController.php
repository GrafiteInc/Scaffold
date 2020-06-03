<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
