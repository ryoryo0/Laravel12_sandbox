# データベース構造 (ER図)

## 概要
Laravel12プロジェクトのデータベース構造を示すEntity-Relationship図です。
このシステムは管理者による商品管理システムとして設計されています。

## 主要エンティティ

### 1. 管理者系テーブル

#### `admins` (管理者)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | 管理者ID |
| name | varchar(255) | NOT NULL | 管理者名 |
| email | varchar(255) | UNIQUE, NOT NULL | メールアドレス |
| email_verified_at | timestamp | NULL | メール認証日時 |
| password | varchar(255) | NOT NULL | パスワード |
| role_id | int | NOT NULL | 役職ID |
| remember_token | varchar(100) | NULL | Remember Token |
| created_at | timestamp | NULL | 作成日時 |
| updated_at | timestamp | NULL | 更新日時 |
| deleted_at | timestamp | NULL | 削除日時 (ソフトデリート) |

#### `admin_password_reset_tokens` (管理者パスワードリセット)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| email | varchar(255) | PK | メールアドレス |
| token | varchar(255) | NOT NULL | リセットトークン |
| created_at | timestamp | NULL | 作成日時 |

#### `admin_sessions` (管理者セッション)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | varchar(255) | PK | セッションID |
| user_id | bigint | INDEX | ユーザーID |
| ip_address | varchar(45) | NULL | IPアドレス |
| user_agent | text | NULL | ユーザーエージェント |
| payload | longtext | NOT NULL | ペイロード |
| last_activity | int | INDEX | 最終アクティビティ |

### 2. 顧客系テーブル

#### `customers` (顧客)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | 顧客ID |
| name | varchar(255) | NOT NULL | 顧客名 |
| email | varchar(255) | UNIQUE, NOT NULL | メールアドレス |
| email_verified_at | timestamp | NULL | メール認証日時 |
| password | varchar(255) | NOT NULL | パスワード |
| remember_token | varchar(100) | NULL | Remember Token |
| created_at | timestamp | NULL | 作成日時 |
| updated_at | timestamp | NULL | 更新日時 |
| deleted_at | timestamp | NULL | 削除日時 (ソフトデリート) |

#### `customer_password_reset_tokens` (顧客パスワードリセット)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| email | varchar(255) | PK | メールアドレス |
| token | varchar(255) | NOT NULL | リセットトークン |
| created_at | timestamp | NULL | 作成日時 |

#### `customer_sessions` (顧客セッション)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | varchar(255) | PK | セッションID |
| user_id | bigint | INDEX | ユーザーID |
| ip_address | varchar(45) | NULL | IPアドレス |
| user_agent | text | NULL | ユーザーエージェント |
| payload | longtext | NOT NULL | ペイロード |
| last_activity | int | INDEX | 最終アクティビティ |

### 3. 商品・カテゴリ系テーブル

#### `categories` (カテゴリ)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | カテゴリID |
| name | varchar(255) | NOT NULL | カテゴリー名 |
| create_admin_id | bigint | FK(admins.id) | カテゴリー作成者 |
| created_at | timestamp | NULL | 作成日時 |
| updated_at | timestamp | NULL | 更新日時 |

#### `products` (商品)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | 商品ID |
| name | varchar(255) | NOT NULL | 商品名 |
| description | varchar(255) | NULL | 説明文 |
| detail_json | jsonb | NULL | 詳細JSON |
| create_admin_id | bigint | FK(admins.id) | 商品作成者 |
| code | varchar(255) | NOT NULL | 商品ID |
| ulid | varchar(26) | NOT NULL | ULID |
| is_public | boolean | NOT NULL | 公開・非公開 |
| is_pick_up | boolean | DEFAULT(0) | おすすめ |
| created_at | timestamp | NULL | 作成日時 |
| updated_at | timestamp | NULL | 更新日時 |
| deleted_at | timestamp | NULL | 削除日時 (ソフトデリート) |

#### `category_product` (カテゴリ-商品中間テーブル)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | ID |
| product_id | bigint | FK(products.id), CASCADE | 商品ID |
| category_id | bigint | FK(categories.id), CASCADE | カテゴリーID |
| created_at | timestamp | NULL | 作成日時 |
| updated_at | timestamp | NULL | 更新日時 |

### 4. 画像系テーブル

#### `temporary_images` (一時画像)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | ID |
| original_filename | varchar(255) | NOT NULL | ファイル名 |
| stored_filename | varchar(255) | NOT NULL | ファイル実体の識別 |
| ulid | varchar(26) | NOT NULL | ULID |
| file_path | varchar(255) | NOT NULL | 画像パス |
| file_size | varchar(255) | NOT NULL | ファイルサイズ |
| file_extension | varchar(255) | NOT NULL | 拡張子 |
| mime_type | varchar(255) | NOT NULL | MIMEタイプ |
| created_at | timestamp | NULL | 作成日時 |
| updated_at | timestamp | NULL | 更新日時 |

#### `product_images` (商品画像)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | ID |
| product_id | int | NOT NULL | 商品ID |
| is_thumbnail | boolean | DEFAULT(false) | サムネイルフラグ |
| original_filename | varchar(255) | NOT NULL | ファイル名 |
| stored_filename | varchar(255) | NOT NULL | ファイル実体の識別 |
| ulid | varchar(26) | NOT NULL | ULID |
| file_path | varchar(255) | NOT NULL | 画像パス |
| file_size | varchar(255) | NOT NULL | ファイルサイズ |
| file_extension | varchar(255) | NOT NULL | 拡張子 |
| mime_type | varchar(255) | NOT NULL | MIMEタイプ |
| created_at | timestamp | NULL | 作成日時 |
| updated_at | timestamp | NULL | 更新日時 |

### 5. 権限管理系テーブル (Spatie Laravel Permission)

#### `permissions` (権限)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | 権限ID |
| name | varchar(255) | NOT NULL | 権限名 |
| guard_name | varchar(255) | NOT NULL | ガード名 |
| created_at | timestamp | NULL | 作成日時 |
| updated_at | timestamp | NULL | 更新日時 |

**ユニーク制約:** (name, guard_name)

#### `roles` (役職)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | 役職ID |
| name | varchar(255) | NOT NULL | 役職名 |
| guard_name | varchar(255) | NOT NULL | ガード名 |
| created_at | timestamp | NULL | 作成日時 |
| updated_at | timestamp | NULL | 更新日時 |

**ユニーク制約:** (name, guard_name)

#### `model_has_permissions` (モデル権限)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| permission_id | bigint | FK(permissions.id), CASCADE | 権限ID |
| model_type | varchar(255) | NOT NULL | モデルタイプ |
| model_id | bigint | NOT NULL | モデルID |

**主キー:** (permission_id, model_id, model_type)

#### `model_has_roles` (モデル役職)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| role_id | bigint | FK(roles.id), CASCADE | 役職ID |
| model_type | varchar(255) | NOT NULL | モデルタイプ |
| model_id | bigint | NOT NULL | モデルID |

**主キー:** (role_id, model_id, model_type)

#### `role_has_permissions` (役職権限)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| permission_id | bigint | FK(permissions.id), CASCADE | 権限ID |
| role_id | bigint | FK(roles.id), CASCADE | 役職ID |

**主キー:** (permission_id, role_id)

### 6. システム系テーブル

#### `cache` (キャッシュ)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| key | varchar(255) | PK | キー |
| value | mediumtext | NOT NULL | 値 |
| expiration | int | NOT NULL | 有効期限 |

#### `cache_locks` (キャッシュロック)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| key | varchar(255) | PK | キー |
| owner | varchar(255) | NOT NULL | 所有者 |
| expiration | int | NOT NULL | 有効期限 |

#### `jobs` (ジョブ)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | ジョブID |
| queue | varchar(255) | INDEX | キュー名 |
| payload | longtext | NOT NULL | ペイロード |
| attempts | tinyint | NOT NULL | 試行回数 |
| reserved_at | int | NULL | 予約日時 |
| available_at | int | NOT NULL | 利用可能日時 |
| created_at | int | NOT NULL | 作成日時 |

#### `job_batches` (ジョブバッチ)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | varchar(255) | PK | バッチID |
| name | varchar(255) | NOT NULL | バッチ名 |
| total_jobs | int | NOT NULL | 総ジョブ数 |
| pending_jobs | int | NOT NULL | 保留ジョブ数 |
| failed_jobs | int | NOT NULL | 失敗ジョブ数 |
| failed_job_ids | longtext | NOT NULL | 失敗ジョブID |
| options | mediumtext | NULL | オプション |
| cancelled_at | int | NULL | キャンセル日時 |
| created_at | int | NOT NULL | 作成日時 |
| finished_at | int | NULL | 完了日時 |

#### `failed_jobs` (失敗ジョブ)
| カラム名 | 型 | 制約 | 説明 |
|---------|-----|------|------|
| id | bigint | PK, AUTO_INCREMENT | ID |
| uuid | varchar(255) | UNIQUE | UUID |
| connection | text | NOT NULL | 接続 |
| queue | text | NOT NULL | キュー |
| payload | longtext | NOT NULL | ペイロード |
| exception | longtext | NOT NULL | 例外 |
| failed_at | timestamp | DEFAULT(CURRENT_TIMESTAMP) | 失敗日時 |

## リレーション図

```
admins (1) ----< categories (N)
   |
   |
   +----------< products (N)
                    |
                    +----< category_product >----< categories
                    |
                    +----< product_images (N)

temporary_images (独立)

permissions (N) >----< role_has_permissions >----< roles (N)
     |                                                |
     |                                                |
     +----< model_has_permissions                     |
                    |                                 |
                    v                                 |
              [polymorphic]                          |
                    |                                 |
                    +----< model_has_roles <----------+
                              |
                              v
                        [polymorphic]
```

## 主要な外部キー制約

1. **categories.create_admin_id** → admins.id
2. **products.create_admin_id** → admins.id
3. **category_product.product_id** → products.id (CASCADE DELETE)
4. **category_product.category_id** → categories.id (CASCADE DELETE)
5. **permissions** ← role_has_permissions → **roles** (CASCADE DELETE)
6. **permissions** ← model_has_permissions → **[polymorphic models]** (CASCADE DELETE)
7. **roles** ← model_has_roles → **[polymorphic models]** (CASCADE DELETE)

## 特記事項

1. **ソフトデリート対応テーブル:** `admins`, `customers`, `products`
2. **ULID使用テーブル:** `products`, `temporary_images`, `product_images`
3. **多対多リレーション:** 商品とカテゴリは`category_product`中間テーブルで関連付け
4. **権限管理:** Spatie Laravel Permissionパッケージを使用
5. **ポリモーフィック関係:** `model_has_permissions`と`model_has_roles`は複数のモデルタイプに対応
6. **画像管理:** 一時画像アップロード機能と商品画像の永続化機能を分離
7. **セッション管理:** 管理者と顧客で別々のセッションテーブルを使用