<?php

namespace App\Http\Controllers\Admin\Product;

use Illuminate\Http\Request;

class TemporaryUploadController
{
    public function __invoke(Request $request)
    {
        $path = $request->file('image')->store('temp');
        return response()->json([
            'url' => asset('storage/' . $path),
            'path' => $path
        ]);
    }
}
