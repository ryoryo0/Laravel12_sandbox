<?php

namespace App\Services\Image;

class ImageProcessingService
{
    /**
     * サムネイルサイズの画像データを作成
     *
     * @param string $realPath 元画像のパス
     * @param int $width サムネイル幅（デフォルト: 200）
     * @param int $height サムネイル高さ（デフォルト: 150）
     * @param bool $bestFit アスペクト比を維持するか（デフォルト: true）
     * @return string 画像バイナリデータ
     */
    public function createThumbnail(string $realPath, int $width = 200, int $height = 150, bool $bestFit = true): string
    {
        $image = new \Imagick($realPath);
        $image->thumbnailImage($width, $height, $bestFit);
        $result = $image->getImageBlob();
        $image->destroy(); // メモリリークを防ぐため

        return $result;
    }

    /**
     * 画像をリサイズ
     *
     * @param string $realPath 元画像のパス
     * @param int $width リサイズ後の幅
     * @param int $height リサイズ後の高さ
     * @param bool $bestFit アスペクト比を維持するか
     * @return string 画像バイナリデータ
     */
    public function resizeImage(string $realPath, int $width, int $height, bool $bestFit = true): string
    {
        $image = new \Imagick($realPath);
        $image->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, $bestFit);
        $result = $image->getImageBlob();
        $image->destroy();

        return $result;
    }

    /**
     * 画像の品質を調整してJPEG形式で出力
     *
     * @param string $realPath 元画像のパス
     * @param int $quality 品質（1-100）
     * @return string 画像バイナリデータ
     */
    public function compressImage(string $realPath, int $quality = 85): string
    {
        $image = new \Imagick($realPath);
        $image->setImageFormat('jpeg');
        $image->setImageCompressionQuality($quality);
        $result = $image->getImageBlob();
        $image->destroy();

        return $result;
    }

    /**
     * 画像情報を取得
     *
     * @param string $realPath 画像のパス
     * @return array 画像情報
     */
    public function getImageInfo(string $realPath): array
    {
        $image = new \Imagick($realPath);

        $info = [
            'width' => $image->getImageWidth(),
            'height' => $image->getImageHeight(),
            'format' => $image->getImageFormat(),
            'filesize' => $image->getImageLength(),
            'compression' => $image->getImageCompression(),
        ];

        $image->destroy();

        return $info;
    }
}