<?php

namespace App\Http\Controllers\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemporaryUploadController
{
    public function __invoke(Request $request)
    {
        $image = new \Imagick($request->file('image')->getRealPath());
        $image->thumbnailImage(200, 150, true);
        $imageData = $image->getImageBlob();
        $dir = 'temporary';
        $filename = 'thumbnail_' . Str::random(16) . '.jpg';
        Storage::disk('public')->put( $dir . '/' . $filename, $imageData);
        $url = asset('storage/' . $dir . '/' . $filename);
        return response()->json([
            'url' => $url,
            'path' => $dir . '/' . $filename
        ]);
    }
}
