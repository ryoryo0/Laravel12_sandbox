<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Product\IndexRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $authUser = Auth::user()->id;
        $query = Product::query()
            ->where('create_admin_id', $authUser);
        $params = $request;
        $this->getQuery($query, $params);
        $products = $query->get();

        $bulkActions = $this->getBulkActionLabels();
        $headings = $this->getHeadingLabels();
        $categories = ProductCategory::query()->pluck('name', 'id');

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
     *
     * @return array
     */
    private function getBulkActionLabels (): array
    {
        $result = [
            'bulk_delete' => 'チェック項目を一括削除',
            'bulk_un_public' => 'チェック項目を一括非公開',
            'bulk_pick_up' => 'チェック項目を一括おすすめ',
        ];

        return $result;
    }

        
    /**
     * テーブルの見出しを取得
     *
     * @return array
     */
    private function getHeadingLabels (): array
    {
        $result = [
            'id',
            '商品名',
            '紹介文',
            '商品コード',
            'カテゴリー',
            '公開',
            '作成日',
        ];

        return $result;
    }


    /**
     * クエリビルダーに絞り込み
     *
     * @param Builder $query クエリビルダーインスタンス
     * @return Builder 絞り込み済みのクエリビルダー
     */
    private function getQuery (Builder $query, $params)
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
