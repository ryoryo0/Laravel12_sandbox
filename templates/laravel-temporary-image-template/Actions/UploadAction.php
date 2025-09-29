<?php

namespace App\Http\Controllers\Admin\Actions\TemporaryFile;

use App\Http\Requests\Admin\TemporaryFile\UploadRequest;
use App\Models\TemporaryFile;
use App\Services\File\FileMetadataService;
use App\Services\Image\ImageProcessingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * 一時ファイルアップロードアクション
 *
 * 機能:
 * - ファイルの一時保存処理
 * - 画像ファイルのリサイズ・最適化
 * - ファイルメタデータの生成・保存
 * - セキュリティチェック
 * - エラーハンドリングとログ記録
 *
 * 処理フロー:
 * 1. ファイル受信とバリデーション
 * 2. メタデータ生成（ULID、ファイル名等）
 * 3. 画像の場合はリサイズ・圧縮処理
 * 4. ストレージへの保存
 * 5. データベースへのメタデータ記録
 * 6. レスポンスデータ生成
 */
class UploadAction
{
    /** @var string 一時ファイル保存ディレクトリ */
    const STORAGE_DIRECTORY = 'temporary';

    /** @var int デフォルトサムネイル幅 */
    const DEFAULT_THUMBNAIL_WIDTH = 200;

    /** @var int デフォルトサムネイル高さ */
    const DEFAULT_THUMBNAIL_HEIGHT = 150;

    public function __construct(
        private FileMetadataService $fileMetadataService,
        private ImageProcessingService $imageProcessingService
    ) {}

    /**
     * ファイルアップロード処理を実行
     *
     * @param UploadRequest $request バリデーション済みリクエスト
     * @return JsonResponse
     */
    public function execute(UploadRequest $request): JsonResponse
    {
        try {
            $uploadedFile = $request->file('file');

            // ファイルタイプに応じた処理分岐
            if ($this->isImageFile($uploadedFile)) {
                return $this->processImageFile($uploadedFile);
            } else {
                return $this->processRegularFile($uploadedFile);
            }

        } catch (Throwable $e) {
            Log::error('Temporary file upload failed', [
                'error' => $e->getMessage(),
                'file_name' => $request->file('file')?->getClientOriginalName(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'error' => 'Upload failed',
                'message' => 'ファイルのアップロードに失敗しました。'
            ], 500);
        }
    }

    /**
     * 画像ファイルを処理
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return JsonResponse
     */
    private function processImageFile($file): JsonResponse
    {
        // ファイルメタデータ生成
        $metadata = $this->fileMetadataService->createFileMetadata(
            $file,
            self::STORAGE_DIRECTORY,
            $this->getOptimizedExtension($file)
        );

        // 画像を最適化して保存
        $optimizedImageData = $this->imageProcessingService->createThumbnail(
            $file->getRealPath(),
            self::DEFAULT_THUMBNAIL_WIDTH,
            self::DEFAULT_THUMBNAIL_HEIGHT,
            true
        );

        // ストレージに保存
        Storage::disk('public')->put($metadata['file_path'], $optimizedImageData);

        // データベースに記録
        $temporaryFile = $this->saveToDatabase($metadata);

        // レスポンスデータ生成
        $responseData = $this->createSuccessResponse($metadata, $temporaryFile);

        Log::info('Image file uploaded successfully', [
            'file_ulid' => $metadata['ulid'],
            'original_size' => $metadata['file_size'],
            'user_id' => auth()->id()
        ]);

        return response()->json($responseData);
    }

    /**
     * 一般ファイルを処理
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return JsonResponse
     */
    private function processRegularFile($file): JsonResponse
    {
        // ファイルメタデータ生成
        $metadata = $this->fileMetadataService->createFileMetadata(
            $file,
            self::STORAGE_DIRECTORY,
            $file->getClientOriginalExtension()
        );

        // ストレージに保存（そのまま）
        Storage::disk('public')->put($metadata['file_path'], file_get_contents($file->getRealPath()));

        // データベースに記録
        $temporaryFile = $this->saveToDatabase($metadata);

        // レスポンスデータ生成
        $responseData = $this->createSuccessResponse($metadata, $temporaryFile);

        Log::info('Regular file uploaded successfully', [
            'file_ulid' => $metadata['ulid'],
            'file_type' => $metadata['mime_type'],
            'user_id' => auth()->id()
        ]);

        return response()->json($responseData);
    }

    /**
     * ファイルが画像かどうかを判定
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return bool
     */
    private function isImageFile($file): bool
    {
        $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
        return in_array($file->getMimeType(), $imageTypes);
    }

    /**
     * 最適化後の拡張子を取得
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    private function getOptimizedExtension($file): string
    {
        // 画像は基本的にJPEGに統一（PNG透過が必要な場合は除く）
        $mimeType = $file->getMimeType();

        if ($mimeType === 'image/png') {
            // PNG透過チェック（簡易版）
            return 'png'; // より詳細な透過チェックが必要な場合は実装
        }

        return 'jpg'; // 他の画像形式はJPEGに統一
    }

    /**
     * データベースにメタデータを保存
     *
     * @param array $metadata
     * @return TemporaryFile
     */
    private function saveToDatabase(array $metadata): TemporaryFile
    {
        $temporaryFile = new TemporaryFile();
        $temporaryFile->fill($metadata);
        $temporaryFile->save();

        return $temporaryFile;
    }

    /**
     * 成功レスポンスデータを生成
     *
     * @param array $metadata
     * @param TemporaryFile $temporaryFile
     * @return array
     */
    private function createSuccessResponse(array $metadata, TemporaryFile $temporaryFile): array
    {
        $baseResponse = $this->fileMetadataService->createResponseData($metadata);

        // 追加のメタデータ情報
        $baseResponse['metadata'] = [
            'id' => $temporaryFile->id,
            'size_formatted' => $this->fileMetadataService->formatFileSize($metadata['file_size']),
            'uploaded_at' => $temporaryFile->created_at->toISOString(),
            'is_image' => $this->isImageMimeType($metadata['mime_type']),
        ];

        return $baseResponse;
    }

    /**
     * MIMEタイプが画像かどうかを判定
     *
     * @param string $mimeType
     * @return bool
     */
    private function isImageMimeType(string $mimeType): bool
    {
        return strpos($mimeType, 'image/') === 0;
    }
}