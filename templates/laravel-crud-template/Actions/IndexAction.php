<?php

namespace App\Http\Controllers\Admin\Actions\Entity;

use App\Http\Requests\Admin\Entity\IndexRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * エンティティ一覧表示アクション
 *
 * 機能:
 * - ページネーション対応一覧表示
 * - 複数条件での検索・フィルタリング
 * - ソート機能
 * - バルクアクション定義
 * - 関連データ（カテゴリ等）の取得
 */
class IndexAction
{
    /** @var int ページネーションの件数 */
    const PAGINATE = 15;

    /**
     * 一覧表示処理を実行
     *
     * @param IndexRequest $request 検索・フィルタパラメータ
     * @return View
     */
    public function execute(IndexRequest $request): View
    {
        $adminUser = Auth::user();

        // 基本クエリ構築（認証ユーザーに関連するデータのみ）
        $query = $adminUser->entities()->with('categories');

        // 検索・フィルタ条件適用
        $this->applyFilters($query, $request);

        // ページネーション実行
        $entities = $query->paginate(self::PAGINATE);
        $entities->appends($request->query());

        // 関連データ取得
        $categories = $adminUser->categories()->pluck('name', 'id');

        // UI用データ準備
        $bulkActions = $this->getBulkActionLabels();
        $headings = $this->getTableHeadings();

        return view('admin.entity.index')
            ->with([
                'entities' => $entities,
                'bulkActions' => $bulkActions,
                'headings' => $headings,
                'categories' => $categories,
            ]);
    }

    /**
     * バルクアクション選択肢を取得
     *
     * @return array
     */
    private function getBulkActionLabels(): array
    {
        return [
            'bulk_delete' => 'チェック項目を一括削除',
            'bulk_un_public' => 'チェック項目を一括非公開',
            'bulk_public' => 'チェック項目を一括公開',
            'bulk_featured' => 'チェック項目を一括おすすめ',
            'bulk_unfeatured' => 'チェック項目のおすすめを解除',
        ];
    }

    /**
     * テーブルヘッダーを取得
     *
     * @return array
     */
    private function getTableHeadings(): array
    {
        return [
            'タイトル',
            '説明',
            'コード',
            'カテゴリー',
            'ステータス',
            '作成日',
            '操作',
        ];
    }

    /**
     * 検索・フィルタ条件をクエリに適用
     *
     * @param Builder $query
     * @param IndexRequest $request
     * @return void
     */
    private function applyFilters(Builder $query, IndexRequest $request): void
    {
        // ID検索
        if ($request->input('id')) {
            $query->where('id', $request->input('id'));
        }

        // 名前検索（部分一致）
        if ($request->input('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        // コード検索（完全一致）
        if ($request->input('code')) {
            $query->where('code', $request->input('code'));
        }

        // 説明文検索（部分一致）
        if ($request->input('description')) {
            $query->where('description', 'LIKE', '%' . $request->input('description') . '%');
        }

        // カテゴリフィルタ（複数選択対応）
        if ($request->input('category_ids')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->whereIn('categories.id', $request->input('category_ids'));
            });
        }

        // 公開状態フィルタ
        if ($request->input('is_public') !== null) {
            $query->where('is_public', $request->input('is_public'));
        }

        // おすすめフィルタ
        if ($request->input('is_featured') !== null) {
            $query->where('is_featured', $request->input('is_featured'));
        }

        // 作成日範囲フィルタ
        if ($request->input('created_from')) {
            $query->whereDate('created_at', '>=', $request->input('created_from'));
        }

        if ($request->input('created_to')) {
            $query->whereDate('created_at', '<=', $request->input('created_to'));
        }

        // ソート（デフォルト: 作成日降順）
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
    }
}