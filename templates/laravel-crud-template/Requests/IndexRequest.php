<?php

namespace App\Http\Requests\Admin\Entity;

use Illuminate\Foundation\Http\FormRequest;

/**
 * エンティティ一覧・検索リクエスト
 *
 * 機能:
 * - 検索・フィルタパラメータのバリデーション
 * - ソート条件のバリデーション
 * - ページネーションパラメータの処理
 */
class IndexRequest extends FormRequest
{
    /**
     * リクエストが認可されているかを判定
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // 認証済みユーザーのみアクセス可能
    }

    /**
     * バリデーションルールを取得
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // 基本検索
            'id' => 'nullable|integer|min:1',
            'name' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',

            // カテゴリフィルタ（複数選択）
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',

            // ステータスフィルタ
            'is_public' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',

            // 日付範囲フィルタ
            'created_from' => 'nullable|date',
            'created_to' => 'nullable|date|after_or_equal:created_from',

            // ソート条件
            'sort_by' => 'nullable|string|in:id,name,code,created_at,updated_at',
            'sort_order' => 'nullable|string|in:asc,desc',

            // ページネーション
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
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
            'id.integer' => 'IDは数値で入力してください。',
            'id.min' => 'IDは1以上で入力してください。',
            'name.max' => '名前は255文字以内で入力してください。',
            'code.max' => 'コードは100文字以内で入力してください。',
            'description.max' => '説明は500文字以内で入力してください。',
            'category_ids.array' => 'カテゴリIDは配列で指定してください。',
            'category_ids.*.integer' => 'カテゴリIDは数値で指定してください。',
            'category_ids.*.exists' => '指定されたカテゴリが存在しません。',
            'created_from.date' => '作成日開始は正しい日付形式で入力してください。',
            'created_to.date' => '作成日終了は正しい日付形式で入力してください。',
            'created_to.after_or_equal' => '作成日終了は開始日以降の日付を指定してください。',
            'sort_by.in' => 'ソート項目が不正です。',
            'sort_order.in' => 'ソート順序は昇順(asc)または降順(desc)で指定してください。',
            'page.integer' => 'ページ番号は数値で指定してください。',
            'page.min' => 'ページ番号は1以上で指定してください。',
            'per_page.integer' => '表示件数は数値で指定してください。',
            'per_page.min' => '表示件数は1以上で指定してください。',
            'per_page.max' => '表示件数は100以下で指定してください。',
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
        if ($this->has('is_public')) {
            $this->merge([
                'is_public' => $this->boolean('is_public')
            ]);
        }

        if ($this->has('is_featured')) {
            $this->merge([
                'is_featured' => $this->boolean('is_featured')
            ]);
        }
    }
}