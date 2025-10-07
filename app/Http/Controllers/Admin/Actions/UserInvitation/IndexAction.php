<?php

namespace App\Http\Controllers\Admin\Actions\UserInvitation;

use App\Models\AdminInvitation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexAction
{
    const PAGINATE = 20;

    /**
     * 招待一覧を表示
     */
    public function execute(Request $request): View
    {
        $invitations = AdminInvitation::with('admin')
            ->latest()
            ->paginate(self::PAGINATE);

        return view('admin.user-invitation.index', compact('invitations'));
    }
}
