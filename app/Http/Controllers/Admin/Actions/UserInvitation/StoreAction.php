<?php

namespace App\Http\Controllers\Admin\Actions\UserInvitation;

use App\Mail\UserInvitationMail;
use App\Models\AdminInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StoreAction
{
    /**
     * 招待メールを送信
     */
    public function execute(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        // 有効期限を計算
        $expirationHours = config('invitation.token_expiration_hours');
        $expiresAt = now()->addHours($expirationHours);

        // 招待レコードを作成
        $invitation = AdminInvitation::create([
            'email' => $request->email,
            'token' => AdminInvitation::generateToken(),
            'expires_at' => $expiresAt,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        // 登録用URLを生成
        $registrationUrl = route('user.register.form', ['token' => $invitation->token]);

        // メールを送信
        Mail::to($invitation->email)->send(new UserInvitationMail($invitation, $registrationUrl));

        return redirect()->route('admin.user-invitation.create')
            ->with('success', '招待メールを送信しました。');
    }
}
