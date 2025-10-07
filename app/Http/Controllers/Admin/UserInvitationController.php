<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Actions\UserInvitation\CreateAction;
use App\Http\Controllers\Admin\Actions\UserInvitation\IndexAction;
use App\Http\Controllers\Admin\Actions\UserInvitation\StoreAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserInvitationController extends Controller
{
    public function __construct(
        private IndexAction $indexAction,
        private CreateAction $createAction,
        private StoreAction $storeAction
    ) {}

    /**
     * 招待一覧を表示
     */
    public function index(Request $request): View
    {
        return $this->indexAction->execute($request);
    }

    /**
     * 招待フォームを表示
     */
    public function create(Request $request): View
    {
        return $this->createAction->execute($request);
    }

    /**
     * 招待メールを送信
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->storeAction->execute($request);
    }
}
