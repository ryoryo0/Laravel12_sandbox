<?php

namespace App\Http\Controllers\Actions\UserRegistration;

use App\Models\AdminInvitation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowFormAction
{
    /**
     * 登録フォームを表示
     */
    public function execute(Request $request): View
    {
        $token = $request->query('token');

        // トークンの検証
        $invitation = AdminInvitation::where('token', $token)
            ->valid()
            ->first();

        if (!$invitation) {
            return view('user.register-error', [
                'message' => '招待リンクが無効です。リンクの有効期限が切れているか、既に使用済みの可能性があります。'
            ]);
        }

        return view('user.register', [
            'invitation' => $invitation,
            'token' => $token,
        ]);
    }
}
