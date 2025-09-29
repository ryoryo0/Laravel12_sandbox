# Laravel Temporary File Upload Template

このテンプレートは、Laravelプロジェクトで安全で高機能な一時ファイルアップロード機能を素早く実装するためのボイラープレートです。画像最適化、セキュリティチェック、メタデータ管理などの実用的な機能を含んでいます。

## 🚀 機能概要

### 基本機能
- ✅ 単一・複数ファイルアップロード
- ✅ ファイル情報取得API
- ✅ ファイル削除機能
- ✅ 一時ファイル自動クリーンアップ

### セキュリティ機能
- ✅ ファイル形式制限・検証
- ✅ ファイルサイズ制限
- ✅ 危険な拡張子の排除
- ✅ MIMEタイプ検証
- ✅ 二重拡張子チェック

### 画像処理機能
- ✅ 自動リサイズ・圧縮
- ✅ サムネイル生成
- ✅ 画質調整
- ✅ 形式変換（JPEG統一）

### メタデータ管理
- ✅ ULID生成・管理
- ✅ ファイル情報の詳細記録
- ✅ アップロード統計
- ✅ 画像寸法・プロパティ取得

## 📁 ファイル構成

```
templates/laravel-temporary-image-template/
├── Controllers/
│   └── TemporaryFileController.php    # 統合コントローラー
├── Actions/                           # ビジネスロジック分離
│   ├── UploadAction.php              # アップロード処理
│   ├── ShowAction.php                # ファイル情報取得
│   └── DeleteAction.php              # 削除・クリーンアップ
├── Requests/
│   └── UploadRequest.php             # アップロードバリデーション
└── README.md                         # このファイル
```

## 🔧 使用方法

### 1. ファイルのコピーと置換

```bash
# プロジェクトにテンプレートをコピー
cp -r templates/laravel-temporary-image-template/* app/Http/Controllers/Admin/

# 以下の文字列を適切な名前に置換
# TemporaryFile → 実際のモデル名（例：TemporaryImage, TempDocument）
# temporary-file → URL・ルート名（例：temporary-image, temp-document）
# TemporaryFileController → コントローラー名
```

### 2. 主要な置換作業

#### 基本的な置換対象
| 置換前 | 置換後の例 | 説明 |
|--------|-----------|------|
| `TemporaryFile` | `TemporaryImage` | モデルクラス名 |
| `temporary-file` | `temporary-image` | ルート・URL |
| `TemporaryFileController` | `TemporaryImageController` | コントローラー名 |
| `TemporaryFile/` | `TemporaryImage/` | 名前空間ディレクトリ |

#### 設定のカスタマイズ
```php
// UploadAction.php内の定数
const STORAGE_DIRECTORY = 'temporary-images';        // 保存ディレクトリ
const DEFAULT_THUMBNAIL_WIDTH = 400;                // サムネイル幅
const DEFAULT_THUMBNAIL_HEIGHT = 300;               // サムネイル高さ

// UploadRequest.php内の定数
const MAX_FILE_SIZE = 10240;                        // 最大ファイルサイズ(KB)
const MAX_IMAGE_SIZE = 5120;                        // 画像最大サイズ(KB)
```

### 3. データベース設定

必要なテーブル構造:

```sql
CREATE TABLE temporary_files (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    original_filename VARCHAR(255) NOT NULL COMMENT 'オリジナルファイル名',
    stored_filename VARCHAR(255) NOT NULL COMMENT '保存ファイル名',
    ulid VARCHAR(26) NOT NULL UNIQUE COMMENT 'ULID識別子',
    file_path VARCHAR(500) NOT NULL COMMENT 'ファイルパス',
    file_size BIGINT NOT NULL COMMENT 'ファイルサイズ(bytes)',
    file_extension VARCHAR(10) NOT NULL COMMENT 'ファイル拡張子',
    mime_type VARCHAR(100) NOT NULL COMMENT 'MIMEタイプ',

    -- オプション：カテゴリ分類
    category ENUM('image', 'document', 'archive', 'other') DEFAULT 'other',

    -- オプション：ユーザー関連付け
    uploaded_by BIGINT NULL COMMENT 'アップロードユーザーID',

    -- オプション：画像メタデータ
    image_width INT NULL COMMENT '画像幅',
    image_height INT NULL COMMENT '画像高さ',

    -- オプション：使用状況管理
    is_used BOOLEAN DEFAULT FALSE COMMENT '使用済みフラグ',
    used_at TIMESTAMP NULL COMMENT '使用日時',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_ulid (ulid),
    INDEX idx_created_at (created_at),
    INDEX idx_category (category),
    INDEX idx_uploaded_by (uploaded_by)
);
```

### 4. モデル設定

```php
// app/Models/TemporaryFile.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{
    protected $fillable = [
        'original_filename',
        'stored_filename',
        'ulid',
        'file_path',
        'file_size',
        'file_extension',
        'mime_type',
        'category',
        'uploaded_by',
        'image_width',
        'image_height',
        'is_used',
        'used_at'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'image_width' => 'integer',
        'image_height' => 'integer',
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    // アップロードユーザーとのリレーション
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ファイルサイズを人間が読みやすい形式で取得
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    // 画像ファイルかどうかを判定
    public function getIsImageAttribute(): bool
    {
        return strpos($this->mime_type, 'image/') === 0;
    }

    // ファイルの完全URLを取得
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
```

### 5. ルート設定

```php
// routes/admin.php
Route::prefix('/temporary-file')->name('temporary-file.')->group(function () {
    // 基本機能
    Route::post('/upload', [TemporaryFileController::class, '__invoke'])->name('upload');
    Route::get('/show/{ulid}', [TemporaryFileController::class, 'show'])->name('show');
    Route::delete('/delete/{ulid}', [TemporaryFileController::class, 'destroy'])->name('delete');

    // 拡張機能（オプション）
    Route::post('/upload-multiple', [TemporaryFileController::class, 'uploadMultiple'])->name('upload-multiple');
    Route::delete('/delete-multiple', [TemporaryFileController::class, 'deleteMultiple'])->name('delete-multiple');
});

// API用ルート（CSRF除外が必要な場合）
Route::prefix('/api/temporary-file')->name('api.temporary-file.')->group(function () {
    Route::post('/upload', [TemporaryFileController::class, '__invoke'])->name('upload');
    Route::get('/show/{ulid}', [TemporaryFileController::class, 'show'])->name('show');
});
```

### 6. 設定ファイル

```php
// config/temporary_files.php
<?php

return [
    // ストレージ設定
    'storage_disk' => 'public',
    'storage_directory' => 'temporary-files',

    // ファイルサイズ制限（KB）
    'max_file_size' => 10240,          // 10MB
    'max_image_size' => 5120,          // 5MB
    'max_document_size' => 20480,      // 20MB

    // 画像処理設定
    'image_optimization' => [
        'enabled' => true,
        'quality' => 85,
        'max_width' => 1920,
        'max_height' => 1080,
        'thumbnail_width' => 400,
        'thumbnail_height' => 300,
    ],

    // 許可されるファイル形式
    'allowed_extensions' => [
        'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'],
        'documents' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
        'archives' => ['zip', 'rar', '7z'],
        'others' => ['txt', 'csv'],
    ],

    // 自動削除設定
    'auto_cleanup' => [
        'enabled' => true,
        'older_than_hours' => 48,
        'max_delete_age_hours' => 24,
    ],

    // セキュリティ設定
    'security' => [
        'scan_uploads' => false,
        'quarantine_suspicious' => true,
        'log_all_uploads' => true,
    ],
];
```

## 🎯 使用例

### フロントエンド（JavaScript）

```javascript
// 単一ファイルアップロード
async function uploadFile(file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('category', 'image');
    formData.append('resize_images', true);

    try {
        const response = await fetch('/admin/temporary-file/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const result = await response.json();

        if (response.ok) {
            console.log('Upload success:', result);
            // result.url, result.ulid 等を使用
            showPreview(result.url, result.ulid);
        } else {
            console.error('Upload failed:', result.message);
        }
    } catch (error) {
        console.error('Upload error:', error);
    }
}

// ファイル情報取得
async function getFileInfo(ulid) {
    try {
        const response = await fetch(`/admin/temporary-file/show/${ulid}`);
        const fileInfo = await response.json();

        if (response.ok) {
            console.log('File info:', fileInfo);
            return fileInfo;
        }
    } catch (error) {
        console.error('Get file info error:', error);
    }
}

// ファイル削除
async function deleteFile(ulid) {
    try {
        const response = await fetch(`/admin/temporary-file/delete/${ulid}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (response.ok) {
            console.log('Delete success:', result);
            removePreview(ulid);
        }
    } catch (error) {
        console.error('Delete error:', error);
    }
}
```

### バックエンド（自動クリーンアップ）

```php
// app/Console/Commands/CleanupTemporaryFiles.php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\Actions\TemporaryFile\DeleteAction;

class CleanupTemporaryFiles extends Command
{
    protected $signature = 'temporary-files:cleanup {--hours=48}';
    protected $description = 'Clean up old temporary files';

    public function handle(DeleteAction $deleteAction)
    {
        $hours = (int) $this->option('hours');

        $this->info("Cleaning up temporary files older than {$hours} hours...");

        $result = $deleteAction->cleanupOldFiles($hours);

        $this->info("Cleanup completed:");
        $this->info("- Found: {$result['total_found']} files");
        $this->info("- Deleted: {$result['deleted']} files");
        $this->info("- Errors: {$result['errors']} files");

        if ($result['errors'] > 0) {
            $this->warn("Some files could not be deleted. Check logs for details.");
        }
    }
}

// app/Console/Kernel.php でスケジュール設定
protected function schedule(Schedule $schedule)
{
    // 毎日深夜2時に48時間以上古いファイルを削除
    $schedule->command('temporary-files:cleanup --hours=48')
             ->dailyAt('02:00');
}
```

## 🛡️ セキュリティ考慮事項

### 1. ファイル検証
- **拡張子チェック**: 危険な実行ファイル形式を拒否
- **MIMEタイプ検証**: Content-Typeヘッダーの検証
- **二重拡張子**: `file.php.jpg` のような偽装を検出
- **ファイルサイズ**: DoS攻撃を防ぐためのサイズ制限

### 2. ストレージセキュリティ
- **直接実行防止**: アップロードディレクトリでのスクリプト実行無効化
- **アクセス制御**: 認証されたユーザーのみアクセス可能
- **パス制限**: ディレクトリトラバーサル攻撃の防止

### 3. ユーザー権限
- **アップロード権限**: 認証済みユーザーのみ
- **削除権限**: ファイル作成者または管理者のみ
- **時間制限**: 一定時間後は削除不可

## 📊 モニタリング・ログ

### ログ出力項目
- アップロード成功/失敗
- ファイル削除操作
- セキュリティ違反の検出
- 自動クリーンアップ結果

### メトリクス収集
- アップロードファイル数・サイズ
- ストレージ使用量
- エラー率
- レスポンス時間

## 🔧 カスタマイズポイント

### 1. 画像処理
- サムネイルサイズ変更
- 画質・圧縮率調整
- 透かし挿入
- EXIF情報の処理

### 2. セキュリティ強化
- ウイルススキャン統合
- ハッシュ値による重複検出
- IP制限・レート制限

### 3. ストレージ拡張
- クラウドストレージ対応（S3、GCS等）
- CDN統合
- 冗長化・バックアップ

### 4. UI/UX改善
- ドラッグ&ドロップ
- プログレスバー
- プレビュー機能
- 一括操作

## 🚨 注意事項

1. **ストレージ容量**: 定期的なクリーンアップが必要
2. **パフォーマンス**: 大容量ファイルの処理時間を考慮
3. **メモリ使用量**: 画像処理時のメモリ制限
4. **権限設定**: ディレクトリ権限の適切な設定

## 📚 関連ドキュメント

- [Laravel ファイルアップロード](https://laravel.com/docs/filesystem)
- [Laravel バリデーション](https://laravel.com/docs/validation)
- [Imagick PHP拡張](https://www.php.net/manual/en/book.imagick.php)
- [セキュアファイルアップロード](https://owasp.org/www-community/vulnerabilities/Unrestricted_File_Upload)