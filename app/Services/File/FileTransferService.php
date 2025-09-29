<?php

namespace App\Services\File;

use Illuminate\Support\Facades\Storage;

class FileTransferService
{
    /**
     * 一時画像を対象ディレクトリへコピーする
     *
     * @param string $sourcePath 元ファイルのパス
     * @param string $targetDir 対象ディレクトリ名
     * @return string|null コピー後のパス、失敗時はnull
     */
    public function copyFileToDirectory(string $sourcePath, string $targetDir): ?string
    {
        // パス部分のみ取得
        $tempRelativePath = str_replace('/storage/', '', parse_url($sourcePath, PHP_URL_PATH));

        if (!$this->fileExists($tempRelativePath)) {
            return null; // 元ファイルが存在しない場合
        }

        // 保存先パス（同じファイル名で保存）
        $fileName = basename($sourcePath);
        $saveRelativePath = 'images/' . $targetDir . '/' . $fileName;

        if (Storage::disk('public')->copy($tempRelativePath, $saveRelativePath)) {
            return $saveRelativePath;
        }

        return null;
    }

    /**
     * ファイルが存在するかチェック
     *
     * @param string $filePath ファイルパス
     * @return bool 存在する場合true
     */
    public function fileExists(string $filePath): bool
    {
        return Storage::disk('public')->exists($filePath);
    }

    /**
     * ファイルを削除
     *
     * @param string $filePath ファイルパス
     * @return bool 削除成功時true
     */
    public function deleteFile(string $filePath): bool
    {
        if ($this->fileExists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return true; // ファイルが存在しない場合も成功とみなす
    }

    /**
     * 複数ファイルを削除
     *
     * @param array $filePaths ファイルパスの配列
     * @return bool 全ての削除が成功した場合true
     */
    public function deleteFiles(array $filePaths): bool
    {
        $success = true;

        foreach ($filePaths as $filePath) {
            if (!$this->deleteFile($filePath)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * ディレクトリを作成
     *
     * @param string $directoryPath ディレクトリパス
     * @return bool 作成成功時true
     */
    public function createDirectory(string $directoryPath): bool
    {
        return Storage::disk('public')->makeDirectory($directoryPath);
    }

    /**
     * ファイルをコピー（任意のパス指定）
     *
     * @param string $sourcePath 元ファイルパス
     * @param string $destinationPath コピー先ファイルパス
     * @return bool コピー成功時true
     */
    public function copyFile(string $sourcePath, string $destinationPath): bool
    {
        if (!$this->fileExists($sourcePath)) {
            return false;
        }

        return Storage::disk('public')->copy($sourcePath, $destinationPath);
    }

    /**
     * ファイルを移動
     *
     * @param string $sourcePath 元ファイルパス
     * @param string $destinationPath 移動先ファイルパス
     * @return bool 移動成功時true
     */
    public function moveFile(string $sourcePath, string $destinationPath): bool
    {
        if (!$this->fileExists($sourcePath)) {
            return false;
        }

        return Storage::disk('public')->move($sourcePath, $destinationPath);
    }

    /**
     * ファイルサイズを取得
     *
     * @param string $filePath ファイルパス
     * @return int|null ファイルサイズ（バイト）、存在しない場合null
     */
    public function getFileSize(string $filePath): ?int
    {
        if (!$this->fileExists($filePath)) {
            return null;
        }

        return Storage::disk('public')->size($filePath);
    }
}