<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\Controller;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    /**
     * 商品に紐づく画像をULIDで取得する
     *
     * @param string $ulid
     * @return JsonResponse
     */
    public function __invoke(string $ulid): JsonResponse
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