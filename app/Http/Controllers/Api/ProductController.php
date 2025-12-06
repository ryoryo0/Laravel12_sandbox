<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * おすすめ商品一覧を取得
     *
     * is_pick_up フラグがtrueの商品、または公開されている商品を返します。
     *
     * @return AnonymousResourceCollection
     */
    public function featured(): AnonymousResourceCollection
    {
        $products = Product::with(['variants', 'images', 'events'])
            ->where('is_public', true) // 公開されている商品のみ
            ->where(function ($query) {
                $query->where('is_pick_up', true) // ピックアップ商品を優先
                      ->orWhereNotNull('id'); // または全ての公開商品
            })
            ->orderBy('updated_at', 'desc') // 新しい商品順
            ->limit(12) // 最大12件
            ->get();
        return ProductResource::collection($products);
    }

    /**
     * 商品一覧を取得（ページネーション付き）
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $products = Product::with(['variants', 'images', 'events'])
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return ProductResource::collection($products);
    }

    /**
     * 商品詳細を取得
     *
     * @param int $id
     * @return ProductResource
     */
    public function show(int $id): ProductResource
    {
        $product = Product::with(['variants', 'images', 'categories', 'events'])
            ->where('is_public', true)
            ->findOrFail($id);

        return new ProductResource($product);
    }
}
