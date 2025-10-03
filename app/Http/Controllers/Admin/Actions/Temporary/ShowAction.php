<?php

namespace App\Http\Controllers\Admin\Actions\Temporary;

use App\Models\TemporaryImage;
use Illuminate\Http\JsonResponse;

class ShowAction
{
    /**
     * ULIDから画像情報を取得する
     */
    public function execute(string $ulid): JsonResponse
    {
        $temporaryImage = TemporaryImage::where('ulid', $ulid)->first();

        if (!$temporaryImage) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        return response()->json([
            'url' => asset('storage/' . $temporaryImage->file_path),
            'name' => $temporaryImage->original_filename,
            'ulid' => $temporaryImage->ulid,
        ]);
    }
}