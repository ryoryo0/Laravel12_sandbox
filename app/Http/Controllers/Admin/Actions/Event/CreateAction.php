<?php

namespace App\Http\Controllers\Admin\Actions\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CreateAction
{
    public function execute(Request $request): View
    {
        $adminUser = Auth::user();

        // すでにイベントが付与されている商品IDを取得
        $productsWithEvents = DB::table('event_product')
            ->pluck('product_id')
            ->unique()
            ->toArray();

        // 管理者の商品から、すでにイベントが付与されている商品を除外
        $products = $adminUser->products()
            ->whereNotIn('products.id', $productsWithEvents)
            ->pluck('name', 'id');

        return view('admin.event.create')
            ->with([
                'products' => $products,
            ]);
    }
}
