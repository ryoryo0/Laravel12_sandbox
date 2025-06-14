<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __invoke()
    {
        $authUser = Auth::user()->id;

        $headings = $this->getHeadingLabels();

        $query = Product::query()
            ->where('is_public', true)
            ->where('create_admin_id', $authUser);

        $this->getQuery($query);

        $products = $query->get();

        return view('admin.product.index')
            ->with([
                'products' => $products,
                'headings'  => $headings,
            ]); 
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
     * クエリビルダーに絞り込み条件を適用
     *
     * @param Builder $query クエリビルダーインスタンス
     * @return Builder 絞り込み済みのクエリビルダー
     */
    private function getQuery (Builder $query)
    {
        $query;
    }
}
