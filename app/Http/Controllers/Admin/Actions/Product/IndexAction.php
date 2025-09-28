<?php

namespace App\Http\Controllers\Admin\Actions\Product;

use App\Http\Requests\Admin\Product\IndexRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class IndexAction
{
    const PAGINATE = 15;

    public function execute(IndexRequest $request): View
    {
        $adminUser = Auth::user();

        $query = $adminUser->products()->with('categories');
        $params = $request;
        $this->getQuery($query, $params);
        $products = $query->paginate(self::PAGINATE);
        $products->appends($request->query());
        $categories = $adminUser->categories()->pluck('name', 'id');
        $bulkActions = $this->getBulkActionLabels();
        $headings = $this->getHeadingLabels();

        return view('admin.product.index')
            ->with([
                'products' => $products,
                'bulkActions' => $bulkActions,
                'headings'  => $headings,
                'categories' => $categories,
            ]);
    }

    /**
     * 一括操作セレクトラベルとkeyの取得
     */
    private function getBulkActionLabels(): array
    {
        return [
            'bulk_delete' => 'チェック項目を一括削除',
            'bulk_un_public' => 'チェック項目を一括非公開',
            'bulk_pick_up' => 'チェック項目を一括おすすめ',
        ];
    }

    /**
     * テーブルの見出しを取得
     */
    private function getHeadingLabels(): array
    {
        return [
            '商品名',
            '紹介文',
            '商品コード',
            'カテゴリー',
            '公開',
            '作成日',
            '操作',
        ];
    }

    /**
     * クエリビルダーに絞り込み
     */
    private function getQuery(Builder $query, $params): void
    {
        if ($params->input('id')) {
            $query->where('id', $params->input('id'));
        }

        if ($params->input('name')) {
            $query->where('name', $params->input('name'));
        }

        if ($params->input('code')) {
            $query->where('code', $params->input('code'));
        }

        if ($params->input('description')) {
            $query->where('description', $params->input('description'));
        }

        if ($params->input('category_ids')) {
            $query->whereIn('category_id', $params->input('category_ids'));
        }

        if ($params->input('is_public')) {
            $query->where('is_public', $params->input('is_public'));
        }

        if ($params->input('is_pick_up')) {
            $query->where('is_pick_up', $params->input('is_pick_up'));
        }
    }
}