<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Actions\User\IndexAction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private IndexAction $indexAction
    ) {}

    /**
     * 管理者一覧を表示
     */
    public function index(Request $request): View
    {
        return $this->indexAction->execute($request);
    }
}
