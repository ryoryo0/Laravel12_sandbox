<?php

namespace App\Http\Controllers\Admin\Actions\Product;

use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ImageAction
{
    /**
     * 商品に紐づく画像をULIDで取得する
     */
    public function execute(string $ulid): JsonResponse
    {
        try {
            // ULIDで画像を検索
            $image = ProductImage::where('ulid', $ulid)->first();

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