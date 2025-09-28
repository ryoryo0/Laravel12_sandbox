<?php

namespace App\Http\Controllers\Admin\Actions\Temporary;

use App\Models\TemporaryImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadAction
{
    const DIRECTORY = 'temporary';

    public function execute(Request $request): JsonResponse
    {
        // テーブルに保存するデータを成形
        $dataList = $this->createSaveData($request->file('image'));
        $temporary = new TemporaryImage();
        $temporary->fill($dataList)->save();
        // 画像をリサイズして保存
        $imageData = $this->createThumbnailImage($request->file('image')->getRealPath());
        Storage::disk('public')->put($dataList['file_path'], $imageData);
        // レスポンスデータを成形
        $responseData = $this->createResponseData($dataList);

        return response()->json($responseData);
    }

    /**
     * 保存データを成形
     */
    private function createSaveData(UploadedFile $file): array
    {
        $ulid = Str::ulid();
        $filename = $ulid . '.jpg';
        $filePath = 'images/' . self::DIRECTORY . '/' . $filename;

        return [
            'original_filename' => $file->getClientOriginalName(),
            'ulid'              => $ulid,
            'stored_filename'   => $filename,
            'file_path'         => $filePath,
            'file_size'         => $file->getSize(),
            'file_extension'    => $file->getClientOriginalExtension(),
            'mime_type'         => $file->getMimeType(),
        ];
    }

    /**
     * サムネサイズの画像データを成形
     */
    private function createThumbnailImage(string $realPath): string
    {
        $image = new \Imagick($realPath);
        $image->thumbnailImage(200, 150, true);
        $result = $image->getImageBlob();

        return $result;
    }

    /**
     * レスポンスデータを成形
     */
    private function createResponseData(array $dataList): array
    {
        return [
            'url'  => asset('storage/' . $dataList['file_path']),
            'name' => $dataList['original_filename'],
            'ulid' => $dataList['ulid'],
        ];
    }
}