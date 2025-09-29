<?php

namespace App\Http\Controllers\Admin\Actions\Entity;

use App\Models\EntityImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * エンティティ画像取得APIアクション
 *
 * 機能:
 * - ULIDによる画像情報取得
 * - JSON形式でのレスポンス
 * - エラーハンドリング
 * - 統一されたAPIレスポンス形式
 */
class ImageAction
{
    /**
     * 画像情報取得処理を実行
     *
     * @param string $ulid 画像のULID
     * @return JsonResponse
     */
    public function execute(string $ulid): JsonResponse
    {
        try {
            // ULIDで画像を検索
            $image = EntityImage::where('ulid', $ulid)->first();

            if (!$image) {
                return response()->json([
                    'error' => 'Image not found',
                    'message' => '指定された画像が見つかりません。'
                ], 404);
            }

            // レスポンスデータ生成
            $responseData = $this->formatImageResponse($image);

            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error('Image retrieval failed', [
                'ulid' => $ulid,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => '画像情報の取得に失敗しました。'
            ], 500);
        }
    }

    /**
     * 画像レスポンスデータをフォーマット
     *
     * @param EntityImage $image
     * @return array
     */
    private function formatImageResponse(EntityImage $image): array
    {
        return [
            'url' => asset('storage/' . $image->file_path),
            'name' => $image->original_filename,
            'ulid' => $image->ulid,
            'metadata' => [
                'size' => $image->file_size,
                'extension' => $image->file_extension,
                'mime_type' => $image->mime_type,
                'is_thumbnail' => $image->is_thumbnail,
                'created_at' => $image->created_at->toISOString(),
            ]
        ];
    }
}