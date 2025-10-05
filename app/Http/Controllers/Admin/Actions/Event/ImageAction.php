<?php

namespace App\Http\Controllers\Admin\Actions\Event;

use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ImageAction
{
    /**
     * イベントに紐づく画像をULIDで取得する
     */
    public function execute(string $ulid): JsonResponse
    {
        try {
            // ULIDでイベントを検索
            $image = EventImage::where('ulid', $ulid)->first();

            if (!$image) {
                return response()->json(['error' => 'Image not found'], 404);
            }


            // TemporaryControllerと同じ形式でレスポンスを返す
            return response()->json([
                'url' => asset('storage/' . $image->file_path),
                'name' => $image->original_filename,
                'ulid' => $image->ulid,
            ]);

        } catch (\Exception $e) {
            Log::error('画像取得エラー: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
