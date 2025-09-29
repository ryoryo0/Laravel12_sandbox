<?php

namespace App\Http\Requests\Admin\TemporaryFile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 一時ファイルアップロードリクエスト
 *
 * 機能:
 * - アップロードファイルのバリデーション
 * - ファイルサイズ・形式制限
 * - セキュリティチェック
 * - 複数ファイル対応
 *
 * セキュリティ要件:
 * - 許可されたファイル形式のみ受け入れ
 * - ファイルサイズ制限
 * - 危険な拡張子の排除
 * - MIMEタイプ検証
 */
class UploadRequest extends FormRequest
{
    /** @var int 最大ファイルサイズ（KB） */
    const MAX_FILE_SIZE = 10240; // 10MB

    /** @var int 画像ファイルの最大サイズ（KB） */
    const MAX_IMAGE_SIZE = 5120; // 5MB

    /** @var int ドキュメントファイルの最大サイズ（KB） */
    const MAX_DOCUMENT_SIZE = 20480; // 20MB

    /**
     * リクエストが認可されているかを判定
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // 認証済みユーザーのみアップロード可能
        return auth()->check();
    }

    /**
     * バリデーションルールを取得
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // 単一ファイルアップロード
            'file' => [
                'required',
                'file',
                'max:' . self::MAX_IMAGE_SIZE,
                'mimes:' . $this->getAllowedMimes(),
                function ($attribute, $value, $fail) {
                    $this->validateFileTypeSpecificSize($attribute, $value, $fail);
                },
                function ($attribute, $value, $fail) {
                    $this->validateFileSecurity($attribute, $value, $fail);
                },
            ],

            // 複数ファイルアップロード（オプション）
            'files' => 'sometimes|array|max:10',
            'files.*' => [
                'file',
                'max:' . self::MAX_FILE_SIZE,
                'mimes:' . $this->getAllowedMimes(),
            ],

            // アップロード設定（オプション）
            'resize_images' => 'sometimes|boolean',
            'quality' => 'sometimes|integer|min:1|max:100',
            'max_width' => 'sometimes|integer|min:100|max:4000',
            'max_height' => 'sometimes|integer|min:100|max:4000',

            // カテゴリ分類（オプション）
            'category' => 'sometimes|string|in:image,document,archive,other',
            'description' => 'sometimes|string|max:500',
        ];
    }

    /**
     * 許可されたMIMEタイプを取得
     *
     * @return string
     */
    private function getAllowedMimes(): string
    {
        $imageTypes = [
            'jpeg', 'jpg', 'png', 'gif', 'webp', 'bmp', 'svg'
        ];

        $documentTypes = [
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'txt', 'rtf', 'odt', 'ods', 'odp'
        ];

        $archiveTypes = [
            'zip', 'rar', '7z', 'tar', 'gz'
        ];

        $audioTypes = [
            'mp3', 'wav', 'ogg', 'aac', 'flac'
        ];

        $videoTypes = [
            'mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'
        ];

        $allTypes = array_merge(
            $imageTypes,
            $documentTypes,
            $archiveTypes,
            $audioTypes,
            $videoTypes
        );

        return implode(',', $allTypes);
    }

    /**
     * ファイルタイプ固有のサイズ制限を検証
     *
     * @param string $attribute
     * @param \Illuminate\Http\UploadedFile $file
     * @param callable $fail
     * @return void
     */
    private function validateFileTypeSpecificSize(string $attribute, $file, callable $fail): void
    {
        $mimeType = $file->getMimeType();
        $fileSizeKB = $file->getSize() / 1024;

        // 画像ファイルのサイズチェック
        if (strpos($mimeType, 'image/') === 0) {
            if ($fileSizeKB > self::MAX_IMAGE_SIZE) {
                $fail("画像ファイルのサイズは" . self::MAX_IMAGE_SIZE . "KB以下にしてください。");
            }
        }

        // ドキュメントファイルのサイズチェック
        $documentMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        if (in_array($mimeType, $documentMimes)) {
            if ($fileSizeKB > self::MAX_DOCUMENT_SIZE) {
                $fail("ドキュメントファイルのサイズは" . self::MAX_DOCUMENT_SIZE . "KB以下にしてください。");
            }
        }
    }

    /**
     * ファイルのセキュリティを検証
     *
     * @param string $attribute
     * @param \Illuminate\Http\UploadedFile $file
     * @param callable $fail
     * @return void
     */
    private function validateFileSecurity(string $attribute, $file, callable $fail): void
    {
        // 危険な拡張子のチェック
        $dangerousExtensions = [
            'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js',
            'jar', 'class', 'php', 'asp', 'aspx', 'jsp'
        ];

        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, $dangerousExtensions)) {
            $fail('このファイル形式はセキュリティ上の理由によりアップロードできません。');
        }

        // ファイル名の検証
        $filename = $file->getClientOriginalName();

        // 制御文字や特殊文字のチェック
        if (preg_match('/[\x00-\x1F\x7F-\x9F]/', $filename)) {
            $fail('ファイル名に無効な文字が含まれています。');
        }

        // 隠しファイルのチェック
        if (strpos($filename, '.') === 0) {
            $fail('隠しファイルはアップロードできません。');
        }

        // 二重拡張子のチェック
        if (substr_count($filename, '.') > 1) {
            $lastDot = strrpos($filename, '.');
            $secondLastDot = strrpos($filename, '.', $lastDot - strlen($filename) - 1);

            if ($secondLastDot !== false) {
                $middleExtension = substr($filename, $secondLastDot + 1, $lastDot - $secondLastDot - 1);
                if (in_array(strtolower($middleExtension), $dangerousExtensions)) {
                    $fail('二重拡張子を含むファイルはアップロードできません。');
                }
            }
        }
    }

    /**
     * バリデーションエラーメッセージを取得
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            // ファイル基本検証
            'file.required' => 'ファイルを選択してください。',
            'file.file' => '有効なファイルを選択してください。',
            'file.max' => 'ファイルサイズは' . self::MAX_FILE_SIZE . 'KB以下にしてください。',
            'file.mimes' => '許可されていないファイル形式です。',

            // 複数ファイル検証
            'files.array' => 'ファイルの選択が正しくありません。',
            'files.max' => 'アップロードできるファイル数は10個までです。',
            'files.*.file' => '有効なファイルを選択してください。',
            'files.*.max' => 'ファイルサイズは' . self::MAX_FILE_SIZE . 'KB以下にしてください。',
            'files.*.mimes' => '許可されていないファイル形式です。',

            // オプション設定
            'resize_images.boolean' => '画像リサイズ設定は真偽値で指定してください。',
            'quality.integer' => '画質は数値で指定してください。',
            'quality.min' => '画質は1以上で指定してください。',
            'quality.max' => '画質は100以下で指定してください。',
            'max_width.integer' => '最大幅は数値で指定してください。',
            'max_width.min' => '最大幅は100px以上で指定してください。',
            'max_width.max' => '最大幅は4000px以下で指定してください。',
            'max_height.integer' => '最大高さは数値で指定してください。',
            'max_height.min' => '最大高さは100px以上で指定してください。',
            'max_height.max' => '最大高さは4000px以下で指定してください。',

            // カテゴリ・説明
            'category.in' => 'カテゴリは指定された値から選択してください。',
            'description.max' => '説明は500文字以内で入力してください。',
        ];
    }

    /**
     * バリデーション前の前処理
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // デフォルト値の設定
        $this->merge([
            'resize_images' => $this->boolean('resize_images', true),
            'quality' => $this->input('quality', 85),
            'max_width' => $this->input('max_width', 1920),
            'max_height' => $this->input('max_height', 1080),
        ]);

        // カテゴリの自動判定
        if (!$this->has('category') && $this->hasFile('file')) {
            $file = $this->file('file');
            $mimeType = $file->getMimeType();

            if (strpos($mimeType, 'image/') === 0) {
                $this->merge(['category' => 'image']);
            } elseif (strpos($mimeType, 'application/') === 0) {
                $this->merge(['category' => 'document']);
            } else {
                $this->merge(['category' => 'other']);
            }
        }
    }

    /**
     * バリデーション後の処理
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        // アップロード情報をログに記録
        \Log::info('File upload validation passed', [
            'user_id' => auth()->id(),
            'file_name' => $this->file('file')?->getClientOriginalName(),
            'file_size' => $this->file('file')?->getSize(),
            'mime_type' => $this->file('file')?->getMimeType(),
            'category' => $this->input('category'),
        ]);
    }
}