<?php

namespace App\Http\Controllers\Admin\Actions\Temporary;

use App\Models\TemporaryImage;
use App\Services\File\FileMetadataService;
use App\Services\Image\ImageProcessingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadAction
{
    const DIRECTORY = 'temporary';

    public function __construct(
        private FileMetadataService $fileMetadataService,
        private ImageProcessingService $imageProcessingService
    ) {}

    public function execute(Request $request): JsonResponse
    {
        // テーブルに保存するデータを成形
        $dataList = $this->fileMetadataService->createFileMetadata(
            $request->file('image'),
            self::DIRECTORY
        );

        $temporary = new TemporaryImage();
        $temporary->fill($dataList)->save();

        // 画像をリサイズして保存
        $imageData = $this->imageProcessingService->createThumbnail(
            $request->file('image')->getRealPath()
        );
        Storage::disk('public')->put($dataList['file_path'], $imageData);

        // レスポンスデータを成形
        $responseData = $this->fileMetadataService->createResponseData($dataList);

        return response()->json($responseData);
    }
}