# Laravel CRUD Template

このテンプレートは、Laravelプロジェクトで汎用的なCRUD機能を素早く実装するためのボイラープレートです。エンティティ管理、画像アップロード、カテゴリ関連付け、リッチテキストエディタなどの一般的な機能を含んでいます。

## 🚀 機能概要

### 基本CRUD機能
- ✅ 一覧表示・ページネーション
- ✅ 詳細表示
- ✅ 作成・編集・削除
- ✅ 検索・フィルタリング
- ✅ ソート機能

### 高度な機能
- ✅ 画像アップロード（サムネイル・複数画像）
- ✅ リッチテキストエディタ（Quill）統合
- ✅ カテゴリ関連付け（多対多）
- ✅ バルクアクション
- ✅ ソフトデリート対応
- ✅ 権限チェック（作成者のみ編集可能）

## 📁 ファイル構成

```
templates/laravel-crud-template/
├── Controllers/
│   └── EntityController.php          # 統合コントローラー
├── Actions/                         # ビジネスロジック分離
│   ├── IndexAction.php             # 一覧表示
│   ├── CreateAction.php            # 作成フォーム
│   ├── StoreAction.php             # 作成処理
│   ├── ShowAction.php              # 詳細表示
│   ├── EditAction.php              # 編集フォーム
│   ├── UpdateAction.php            # 更新処理
│   ├── DestroyAction.php           # 削除処理
│   └── ImageAction.php             # 画像API
├── Requests/                       # フォームリクエスト
│   ├── IndexRequest.php            # 検索・フィルタ
│   ├── StoreRequest.php            # 作成バリデーション
│   └── UpdateRequest.php           # 更新バリデーション
└── README.md                       # このファイル
```

## 🔧 使用方法

### 1. ファイルのコピーと置換

```bash
# プロジェクトにテンプレートをコピー
cp -r templates/laravel-crud-template/* app/Http/Controllers/Admin/

# 以下の文字列を適切な名前に置換
# Entity → 実際のモデル名（例：Product, Article, User）
# entity → モデル名の小文字（例：product, article, user）
# entities → モデル名の複数形（例：products, articles, users）
```

### 2. 必要な置換作業

#### 主要な置換対象
| 置換前 | 置換後の例 | 説明 |
|--------|-----------|------|
| `Entity` | `Product` | モデルクラス名 |
| `entity` | `product` | ルート・ビュー名 |
| `entities` | `products` | テーブル名・複数形 |
| `EntityImage` | `ProductImage` | 画像モデル名 |
| `EntityController` | `ProductController` | コントローラー名 |

#### ディレクトリ構造
```bash
# Actions/ ディレクトリ名を変更
mv Actions/Entity Actions/Product

# 名前空間も更新
# App\Http\Controllers\Admin\Actions\Entity
# ↓
# App\Http\Controllers\Admin\Actions\Product
```

### 3. データベース設定

必要なテーブル構造:

```sql
-- メインエンティティテーブル
CREATE TABLE entities (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    content_json JSON,
    create_admin_id BIGINT NOT NULL,
    update_admin_id BIGINT,
    ulid VARCHAR(26) NOT NULL,
    is_public BOOLEAN DEFAULT 0,
    is_featured BOOLEAN DEFAULT 0,
    sort_order INT DEFAULT 0,
    meta_title VARCHAR(60),
    meta_description VARCHAR(160),
    meta_keywords VARCHAR(255),
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- カテゴリ中間テーブル
CREATE TABLE category_entity (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    entity_id BIGINT NOT NULL,
    category_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (entity_id) REFERENCES entities(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- 画像テーブル
CREATE TABLE entity_images (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    entity_id BIGINT NOT NULL,
    is_thumbnail BOOLEAN DEFAULT 0,
    original_filename VARCHAR(255) NOT NULL,
    stored_filename VARCHAR(255) NOT NULL,
    ulid VARCHAR(26) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size VARCHAR(255) NOT NULL,
    file_extension VARCHAR(255) NOT NULL,
    mime_type VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (entity_id) REFERENCES entities(id) ON DELETE CASCADE
);
```

### 4. モデル設定

```php
// app/Models/Entity.php
class Entity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'description', 'content_json',
        'create_admin_id', 'update_admin_id', 'ulid',
        'is_public', 'is_featured', 'sort_order',
        'meta_title', 'meta_description', 'meta_keywords',
        'published_at'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    // リレーション
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasMany(EntityImage::class);
    }

    public function createAdmin()
    {
        return $this->belongsTo(Admin::class, 'create_admin_id');
    }

    public function updateAdmin()
    {
        return $this->belongsTo(Admin::class, 'update_admin_id');
    }
}
```

### 5. ルート設定

```php
// routes/admin.php
Route::prefix('/entity')->name('entity.')->group(function () {
    Route::get('/', [EntityController::class, 'index'])->name('index');
    Route::get('/create', [EntityController::class, 'create'])->name('create');
    Route::post('/store', [EntityController::class, 'store'])->name('store');
    Route::get('/show/{id}', [EntityController::class, 'show'])->name('show');
    Route::get('/edit/{id}', [EntityController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [EntityController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [EntityController::class, 'destroy'])->name('destroy');
    Route::get('/image/{ulid}', [EntityController::class, 'image'])->name('imageShow');
});
```

## 🎯 設計思想

### Action Pattern
- **単一責任原則**: 各Actionクラスは1つの責務のみを持つ
- **依存性注入**: サービスクラスを注入してビジネスロジックを分離
- **テスタビリティ**: 各Actionクラスを個別にテスト可能

### サービス分離
- **FileTransferService**: ファイル操作の共通化
- **ImageProcessingService**: 画像処理の共通化
- **FileMetadataService**: ファイルメタデータ管理

### バリデーション戦略
- **FormRequestクラス**: 複雑なバリデーションロジックを分離
- **一意制約処理**: 更新時の自分自身を除外する処理
- **前処理・後処理**: データの正規化と整形

## 📝 カスタマイズポイント

### 1. フィールドの追加・削除
- `StoreRequest`と`UpdateRequest`のrulesメソッドを更新
- データベースマイグレーションを作成
- Actionクラスの処理を調整

### 2. 検索条件の追加
- `IndexRequest`にバリデーションルールを追加
- `IndexAction`の`applyFilters`メソッドを更新

### 3. 画像処理のカスタマイズ
- `ImageProcessingService`を拡張
- サムネイルサイズや画質設定を調整

### 4. 権限制御の強化
- 各Actionクラスに権限チェックロジックを追加
- ポリシークラスの実装

## 🔍 活用シーン

- **商品管理システム**
- **記事・ブログ管理**
- **ユーザー管理**
- **コンテンツ管理システム（CMS）**
- **カタログ管理**

## 🚨 注意事項

1. **セキュリティ**: 本番環境では適切な認証・認可の実装が必要
2. **パフォーマンス**: 大量データの場合は検索最適化を検討
3. **画像容量**: ファイルサイズ制限とストレージ容量の管理
4. **バックアップ**: ファイルデータの定期バックアップ設定

## 📚 関連ドキュメント

- [Laravel公式ドキュメント](https://laravel.com/docs)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [フォームリクエスト](https://laravel.com/docs/validation#form-request-validation)
- [ファイルアップロード](https://laravel.com/docs/filesystem)