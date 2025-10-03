<?php

namespace App\Services\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileMetadataService
{
    /**
     * アップロードされたファイルから保存用のメタデータを生成
     *
     * @param UploadedFile $file アップロードファイル
     * @param string $directory 保存ディレクトリ名
     * @param string $extension 保存時の拡張子（デフォルト: jpg）
     * @return array ファイルメタデータ
     */
    public function createFileMetadata(UploadedFile $file, string $directory, string $extension = 'jpg'): array
    {
        $ulid = Str::ulid();
        $filename = $ulid . '.' . $extension;
        $filePath = 'images/' . $directory . '/' . $filename;

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
     * ULIDを生成してファイル名を作成
     *
     * @param string $extension 拡張子
     * @return array ULID、ファイル名、ULIDの配列
     */
    public function generateFileIdentifiers(string $extension = 'jpg'): array
    {
        $ulid = Str::ulid();
        $filename = $ulid . '.' . $extension;

        return [
            'ulid' => $ulid,
            'filename' => $filename,
        ];
    }

    /**
     * ファイルパスを生成
     *
     * @param string $directory ディレクトリ名
     * @param string $filename ファイル名
     * @return string 完全なファイルパス
     */
    public function generateFilePath(string $directory, string $filename): string
    {
        return 'images/' . $directory . '/' . $filename;
    }

    /**
     * APIレスポンス用のデータを生成
     *
     * @param array $fileMetadata ファイルメタデータ
     * @return array レスポンスデータ
     */
    public function createResponseData(array $fileMetadata): array
    {
        return [
            'url'  => asset('storage/' . $fileMetadata['file_path']),
            'name' => $fileMetadata['original_filename'],
            'ulid' => $fileMetadata['ulid'],
        ];
    }

    /**
     * ファイルサイズを人間が読みやすい形式に変換
     *
     * @param int $bytes ファイルサイズ（バイト）
     * @return string 読みやすいファイルサイズ
     */
    public function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * ファイル拡張子からMIMEタイプを推測
     *
     * @param string $extension ファイル拡張子
     * @return string MIMEタイプ
     */
    public function getMimeTypeFromExtension(string $extension): string
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'bmp' => 'image/bmp',
            'tiff' => 'image/tiff',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }
}