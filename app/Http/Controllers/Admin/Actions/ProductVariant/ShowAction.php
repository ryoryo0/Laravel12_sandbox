<?php

namespace App\Http\Controllers\Admin\Actions\ProductVariant;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShowAction
{
    public function execute(ProductVariant $productVariant): View
    {
        $adminUser = Auth::user();

        // 権限チェック：商品の作成者が現在のadminユーザーかをチェック
        if ($productVariant->product->create_admin_id !== $adminUser->id) {
            abort(403, 'この在庫情報にアクセスする権限がありません。');
        }

        // 関連データをロード
        $productVariant->load('product');

        return view('admin.product-variant.show', compact('productVariant'));
    }
}
