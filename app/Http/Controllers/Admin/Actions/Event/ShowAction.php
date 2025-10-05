<?php

namespace App\Http\Controllers\Admin\Actions\Event;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShowAction
{
    public function execute(Event $event): View
    {
        $adminUser = Auth::user();

        // 権限チェック：現在のadminユーザーが作成したイベントかをチェック
        if ($event->create_admin_id !== $adminUser->id) {
            abort(403, 'このイベントにアクセスする権限がありません。');
        }

        // 関連データをロード
        $event->load(['products', 'image']);

        return view('admin.event.show', compact('event'));
    }
}
