<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\TemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemporaryUploadController
{
    const DIRECTORY = 'temporary';

    public function __invoke(Request $request)
    {
        // テーブルに保存するデータを成形
        $dataList = $this->createSaveData($request->file('image'));
        $temporary = new TemporaryImage();
        $temporary->fill($dataList)->save();
        // 画像をリサイズして保存
        $imageData = $this->createThumbnailImage($request->file('image')->getRealPath());
        Storage::disk('public')->put($dataList['file_path'], $imageData);
        // レスポンスデータを成形
        $responseData =  $this->createResponseData($dataList);

        return response()->json($responseData);
    }


    /**
     * ULIDから画像情報を取得する
     *
     * @param string $ulid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $ulid)
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

        
    /**
     * 保存データを成形
     *
     * @param UploadedFile $image アップロードされた画像ファイル
     * @return array $imageDataList
     */
    private function createSaveData (UploadedFile $file): array
    {
        $ulid =  Str::ulid();
        $filename = $ulid . '.jpg';
        $filePath = 'images/' . self::DIRECTORY . '/' . $filename;

        $result = [
            'original_filename' => $file->getClientOriginalName(),
            'ulid'              => $ulid,
            'stored_filename'   => $filename,
            'file_path'         => $filePath,
            'file_size'         => $file->getSize(),
            'file_extension'    => $file->getClientOriginalExtension(),
            'mime_type'         => $file->getMimeType(),
        ];
        return $result;
    }

    
    /**
     * サムネサイズの画像データを成形
     * @param string $realPath
     * @return string $result
     */
    private function createThumbnailImage ($realPath) :string
    {
        $image = new \Imagick($realPath);
        $image->thumbnailImage(200, 150, true);
        $result = $image->getImageBlob();
        
        return $result;
    }


    /**
     * レスポンスデータを成形
     *
     * @param array $dataList
     * @return array $result
     */
    private function createResponseData ($dataList): array
    {
        $result = [
            'url'  => asset('storage/' . $dataList['file_path']),
            'name' => $dataList['original_filename'],
            'ulid' => $dataList['ulid'],
        ];
       
        return $result;
    }
}
