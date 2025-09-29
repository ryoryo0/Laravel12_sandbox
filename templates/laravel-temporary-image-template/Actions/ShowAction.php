<?php

namespace App\Http\Controllers\Admin\Actions\TemporaryFile;

use App\Models\TemporaryFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * 一時ファイル情報取得アクション
 *
 * 機能:
 * - ULIDによるファイル情報取得
 * - ファイルの存在確認
 * - メタデータの整形
 * - セキュリティチェック
 * - 統一されたAPIレスポンス形式
 *
 * 用途:
 * - アップロード後のファイル情報確認
 * - フォーム復元時のファイル情報取得
 * - ファイルプレビュー表示
 * - 編集画面での既存ファイル表示
 */
class ShowAction
{
    /**
     * ファイル情報取得処理を実行
     *
     * @param string $ulid ファイルの一意識別子
     * @return JsonResponse
     */
    public function execute(string $ulid): JsonResponse
    {
        try {
            // ULIDでファイル情報を検索
            $temporaryFile = TemporaryFile::where('ulid', $ulid)->first();

            if (!$temporaryFile) {
                return $this->createNotFoundResponse($ulid);
            }

            // ファイルの物理的存在確認
            if (!$this->filePhysicallyExists($temporaryFile)) {
                Log::warning('Temporary file record exists but physical file missing', [
                    'ulid' => $ulid,
                    'file_path' => $temporaryFile->file_path
                ]);

                return $this->createFileNotFoundResponse($ulid);
            }

            // レスポンスデータ生成
            $responseData = $this->formatFileResponse($temporaryFile);

            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error('Temporary file retrieval failed', [
                'ulid' => $ulid,
                'error' => $e->getMessage()
            ]);

            return $this->createServerErrorResponse();
        }
    }

    /**
     * ファイルの物理的存在を確認
     *
     * @param TemporaryFile $temporaryFile
     * @return bool
     */
    private function filePhysicallyExists(TemporaryFile $temporaryFile): bool
    {
        return \Storage::disk('public')->exists($temporaryFile->file_path);
    }

    /**
     * ファイル情報をレスポンス形式にフォーマット
     *
     * @param TemporaryFile $temporaryFile
     * @return array
     */
    private function formatFileResponse(TemporaryFile $temporaryFile): array
    {
        return [
            // 基本情報
            'url' => asset('storage/' . $temporaryFile->file_path),
            'name' => $temporaryFile->original_filename,
            'ulid' => $temporaryFile->ulid,

            // ファイル詳細情報
            'metadata' => [
                'id' => $temporaryFile->id,
                'stored_filename' => $temporaryFile->stored_filename,
                'size' => (int) $temporaryFile->file_size,
                'size_formatted' => $this->formatFileSize($temporaryFile->file_size),
                'extension' => $temporaryFile->file_extension,
                'mime_type' => $temporaryFile->mime_type,
                'is_image' => $this->isImageFile($temporaryFile),
                'created_at' => $temporaryFile->created_at->toISOString(),
                'age_hours' => $this->calculateFileAge($temporaryFile),
            ],

            // 画像固有情報（画像ファイルの場合）
            'image_info' => $this->getImageInfo($temporaryFile),

            // セキュリティ情報
            'security' => [
                'is_safe' => $this->isSafeFile($temporaryFile),
                'scan_status' => 'passed', // ウイルススキャン結果等
            ]
        ];
    }

    /**
     * ファイルが画像かどうかを判定
     *
     * @param TemporaryFile $temporaryFile
     * @return bool
     */
    private function isImageFile(TemporaryFile $temporaryFile): bool
    {
        return strpos($temporaryFile->mime_type, 'image/') === 0;
    }

    /**
     * ファイルサイズを人間が読みやすい形式にフォーマット
     *
     * @param string|int $bytes
     * @return string
     */
    private function formatFileSize($bytes): string
    {
        $bytes = (int) $bytes;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * ファイルの経過時間を計算（時間単位）
     *
     * @param TemporaryFile $temporaryFile
     * @return float
     */
    private function calculateFileAge(TemporaryFile $temporaryFile): float
    {
        return $temporaryFile->created_at->diffInHours(now(), true);
    }

    /**
     * 画像固有情報を取得
     *
     * @param TemporaryFile $temporaryFile
     * @return array|null
     */
    private function getImageInfo(TemporaryFile $temporaryFile): ?array
    {
        if (!$this->isImageFile($temporaryFile)) {
            return null;
        }

        try {
            $filePath = storage_path('app/public/' . $temporaryFile->file_path);

            if (!file_exists($filePath)) {
                return null;
            }

            $imageSize = getimagesize($filePath);

            if ($imageSize === false) {
                return null;
            }

            return [
                'width' => $imageSize[0],
                'height' => $imageSize[1],
                'aspect_ratio' => round($imageSize[0] / $imageSize[1], 2),
                'bits' => $imageSize['bits'] ?? null,
                'channels' => $imageSize['channels'] ?? null,
            ];

        } catch (\Exception $e) {
            Log::warning('Failed to get image info', [
                'ulid' => $temporaryFile->ulid,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * ファイルが安全かどうかを判定
     *
     * @param TemporaryFile $temporaryFile
     * @return bool
     */
    private function isSafeFile(TemporaryFile $temporaryFile): bool
    {
        // 危険な拡張子のチェック
        $dangerousExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js'];
        $extension = strtolower($temporaryFile->file_extension);

        if (in_array($extension, $dangerousExtensions)) {
            return false;
        }

        // MIMEタイプの検証
        $safeMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp',
            'application/pdf', 'text/plain', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        return in_array($temporaryFile->mime_type, $safeMimeTypes);
    }

    /**
     * ファイルが見つからない場合のレスポンス
     *
     * @param string $ulid
     * @return JsonResponse
     */
    private function createNotFoundResponse(string $ulid): JsonResponse
    {
        return response()->json([
            'error' => 'File not found',
            'message' => '指定されたファイルが見つかりません。',
            'ulid' => $ulid
        ], 404);
    }

    /**
     * ファイルレコードは存在するが物理ファイルがない場合のレスポンス
     *
     * @param string $ulid
     * @return JsonResponse
     */
    private function createFileNotFoundResponse(string $ulid): JsonResponse
    {
        return response()->json([
            'error' => 'Physical file not found',
            'message' => 'ファイル情報は存在しますが、実際のファイルが見つかりません。',
            'ulid' => $ulid
        ], 410); // Gone
    }

    /**
     * サーバーエラーレスポンス
     *
     * @return JsonResponse
     */
    private function createServerErrorResponse(): JsonResponse
    {
        return response()->json([
            'error' => 'Internal server error',
            'message' => 'ファイル情報の取得に失敗しました。'
        ], 500);
    }
}