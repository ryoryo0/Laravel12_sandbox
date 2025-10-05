<?php

namespace App\Http\Controllers\Admin\Actions\Event;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $products = $adminUser->products()->pluck('name', 'id');
        return view('admin.event.edit')
            ->with([
                'products' => $products,
                'event' => $event,
            ]);
    }
}
