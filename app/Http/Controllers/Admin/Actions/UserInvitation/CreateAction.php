<?php

namespace App\Http\Controllers\Admin\Actions\UserInvitation;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CreateAction
{
    /**
     * 招待フォームを表示
     */
    public function execute(Request $request): View
    {
        return view('admin.user-invitation.create');
    }
}
