<?php

namespace App\Http\Controllers\Admin\Actions\User;

use App\Models\Admin;
use App\Models\AdminInvitation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexAction
{
    const PAGINATE = 20;

    /**
     * 管理者一覧を表示
     */
    public function execute(Request $request): View
    {
        $query = Admin::query();

        // 検索機能
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 管理者一覧（ページネーション）
        $admins = $query->latest()->paginate(self::PAGINATE);

        // 登録待ちの管理者（未使用の招待）
        $pendingInvitations = AdminInvitation::with('admin')
            ->valid()
            ->latest()
            ->get();

        return view('admin.user.index', compact('admins', 'pendingInvitations'));
    }
}
