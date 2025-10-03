<?php

namespace App\Http\Requests\Admin\Entity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * エンティティ更新リクエスト
 *
 * 機能:
 * - 更新時のバリデーションルール
 * - 一意制約の除外処理（自分自身を除く）
 * - 画像ファイルのバリデーション
 * - カテゴリ関連付けのバリデーション
 */
class UpdateRequest extends FormRequest
{
    /**
     * リクエストが認可されているかを判定
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // 認証済み管理者のみアクセス可能
    }

    /**
     * バリデーションルールを取得
     *
     * @return array
     */
    public function rules(): array
    {
        $entityId = $this->route('id');

        return [
            // 基本情報
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('entities', 'code')->ignore($entityId)
            ],
            'description' => 'nullable|string|max:1000',

            // リッチテキストコンテンツ
            'content_json' => 'nullable|json',

            // カテゴリ関連付け
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'integer|exists:categories,id',

            // ステータス
            'is_public' => 'required|boolean',
            'is_featured' => 'nullable|boolean',

            // 表示順序
            'sort_order' => 'nullable|integer|min:0|max:9999',

            // 画像関連
            'thumbnail' => 'nullable|string|exists:temporary_images,ulid',
            'additional_images' => 'nullable|array|max:10',
            'additional_images.*' => 'string|exists:temporary_images,ulid',

            // SEO関連
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',

            // 公開日時
            'published_at' => 'nullable|date',

            // 更新理由（オプション）
            'update_reason' => 'nullable|string|max:500',
        ];
    }

    /**
     * バリデーションエラーメッセージを取得
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            // 基本情報
            'name.required' => '名前は必須です。',
            'name.max' => '名前は255文字以内で入力してください。',
            'code.required' => 'コードは必須です。',
            'code.max' => 'コードは100文字以内で入力してください。',
            'code.unique' => 'このコードは既に使用されています。',
            'description.max' => '説明は1000文字以内で入力してください。',

            // リッチテキスト
            'content_json.json' => 'コンテンツの形式が正しくありません。',

            // カテゴリ
            'category_ids.required' => 'カテゴリを少なくとも1つ選択してください。',
            'category_ids.array' => 'カテゴリの選択が正しくありません。',
            'category_ids.min' => 'カテゴリを少なくとも1つ選択してください。',
            'category_ids.*.integer' => 'カテゴリIDが正しくありません。',
            'category_ids.*.exists' => '選択されたカテゴリが存在しません。',

            // ステータス
            'is_public.required' => '公開状態を選択してください。',
            'is_public.boolean' => '公開状態の値が正しくありません。',
            'is_featured.boolean' => 'おすすめ設定の値が正しくありません。',

            // 表示順序
            'sort_order.integer' => '表示順序は数値で入力してください。',
            'sort_order.min' => '表示順序は0以上で入力してください。',
            'sort_order.max' => '表示順序は9999以下で入力してください。',

            // 画像
            'thumbnail.exists' => '指定されたサムネイル画像が存在しません。',
            'additional_images.array' => '追加画像の指定が正しくありません。',
            'additional_images.max' => '追加画像は10枚まで指定できます。',
            'additional_images.*.exists' => '指定された追加画像が存在しません。',

            // SEO
            'meta_title.max' => 'メタタイトルは60文字以内で入力してください。',
            'meta_description.max' => 'メタディスクリプションは160文字以内で入力してください。',
            'meta_keywords.max' => 'メタキーワードは255文字以内で入力してください。',

            // 公開日時
            'published_at.date' => '公開日時は正しい日付形式で入力してください。',

            // 更新理由
            'update_reason.max' => '更新理由は500文字以内で入力してください。',
        ];
    }

    /**
     * バリデーション前の前処理
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // チェックボックスの値を正規化
        $this->merge([
            'is_public' => $this->boolean('is_public'),
            'is_featured' => $this->boolean('is_featured', false),
        ]);

        // 空文字をnullに変換
        $nullableFields = ['content_json', 'description', 'meta_title', 'meta_description', 'meta_keywords', 'update_reason'];
        $updates = [];

        foreach ($nullableFields as $field) {
            if ($this->input($field) === '') {
                $updates[$field] = null;
            }
        }

        if (!empty($updates)) {
            $this->merge($updates);
        }
    }

    /**
     * バリデーション後の処理
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        // 更新時のタイムスタンプを記録
        $this->merge([
            'updated_at' => now(),
        ]);
    }
}