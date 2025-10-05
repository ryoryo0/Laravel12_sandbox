<?php

namespace App\Http\Controllers\Admin\Actions\ProductVariant;

use App\Http\Requests\Admin\ProductVariant\IndexRequest;
use App\Models\ProductVariant;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\View\View;

class IndexAction
{
    const PAGINATE = 15;

    public function execute(IndexRequest $request): View
    {
        $query = ProductVariant::query()->with('product');
        $params = $request;
        $this->getQuery($query, $params);
        $variants = $query->paginate(self::PAGINATE);
        $variants->appends($request->query());
        $bulkActions = $this->getBulkActionLabels();
        $headings = $this->getHeadingLabels();

        return view('admin.product-variant.index')
            ->with([
                'variants' => $variants,
                'bulkActions' => $bulkActions,
                'headings'  => $headings,
            ]);
    }

    /**
     * 一括操作セレクトラベルとkeyの取得
     */
    private function getBulkActionLabels(): array
    {
        return [
            'bulk_delete' => 'チェック項目を一括削除',
        ];
    }

    /**
     * テーブルの見出しを取得
     */
    private function getHeadingLabels(): array
    {
        return [
            '商品名',
            'カラー',
            'サイズ',
            '在庫数',
            '価格',
            '作成日',
            '操作',
        ];
    }

    /**
     * クエリビルダーに絞り込み
     */
    private function getQuery(Builder $query, $params): void
    {
        if ($params->input('product_name')) {
            $query->whereHas('product', function ($q) use ($params) {
                $q->where('name', 'like', '%' . $params->input('product_name') . '%');
            });
        }

        if ($params->input('color')) {
            $query->where('color', 'like', '%' . $params->input('color') . '%');
        }

        if ($params->input('size')) {
            $query->where('size', 'like', '%' . $params->input('size') . '%');
        }
    }
}
