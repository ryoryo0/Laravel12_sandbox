<?php

namespace App\Http\Controllers\Actions\UserRegistration;

use App\Models\AdminInvitation;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterAction
{
    /**
     * ユーザー登録処理
     */
    public function execute(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // トークンの検証
        $invitation = AdminInvitation::where('token', $request->token)
            ->valid()
            ->first();

        if (!$invitation) {
            return back()->withErrors([
                'token' => '招待リンクが無効です。'
            ])->withInput();
        }

        // メールアドレスが招待されたものと一致するか確認
        if ($invitation->email !== $request->email) {
            return back()->withErrors([
                'email' => '招待されたメールアドレスと一致しません。'
            ])->withInput();
        }

        // 管理者を作成
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => Admin::ID_ADMIN,
        ]);

        // 招待を使用済みにマーク
        $invitation->markAsUsed();

        // 管理者としてログイン
        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.home')->with('success', '管理者登録が完了しました。');
    }
}
