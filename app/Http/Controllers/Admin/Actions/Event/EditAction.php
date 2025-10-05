<?php

namespace App\Http\Controllers\Admin\Actions\Event;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EditAction
{
    public function execute(Event $event): View
    {
        $adminUser = Auth::user();

        // 権限チェック：現在のadminユーザーが作成したイベントかをチェック
        if ($event->create_admin_id !== $adminUser->id) {
            abort(403, 'このイベントを編集する権限がありません。');
        }

        // 関連データをロード
        $event->load(['products', 'image']);

        // 他のイベント（現在編集中のイベント以外）に紐づいている商品IDを取得
        $productsWithOtherEvents = DB::table('event_product')
            ->where('event_id', '!=', $event->id)
            ->pluck('product_id')
            ->unique()
            ->toArray();

        // 現在のイベントに紐づいている商品ID
        $currentEventProductIds = $event->products->pluck('id')->toArray();

        // 除外すべき商品ID = 他のイベントに紐づいている商品ID - 現在のイベントに紐づいている商品ID
        $excludeProductIds = array_diff($productsWithOtherEvents, $currentEventProductIds);

        // 管理者の商品から、他のイベントに紐づいている商品を除外
        $products = $adminUser->products()
            ->whereNotIn('products.id', $excludeProductIds)
            ->pluck('name', 'id');

        return view('admin.event.edit')
            ->with([
                'products' => $products,
                'event' => $event,
            ]);
    }
}
