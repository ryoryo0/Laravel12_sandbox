<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShowController
{
    public function __invoke(int $id): View
    {
        $adminUser = Auth::user();

        // 商品が存在し、かつ現在のadminユーザーが作成した商品かをチェック
        $product = Product::with(['categories', 'images'])
            ->where('id', $id)
            ->where('create_admin_id', $adminUser->id)
            ->firstOrFail();

        return view('admin.product.show', compact('product'));
    }
}