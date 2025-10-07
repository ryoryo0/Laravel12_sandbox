<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Actions\UserRegistration\RegisterAction;
use App\Http\Controllers\Actions\UserRegistration\ShowFormAction;
use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserRegistrationController extends Controller
{
    public function __construct(
        private ShowFormAction $showFormAction,
        private RegisterAction $registerAction
    ) {}

    /**
     * 登録フォームを表示
     */
    public function showRegistrationForm(Request $request): View
    {
        return $this->showFormAction->execute($request);
    }

    /**
     * ユーザー登録処理
     */
    public function register(Request $request): RedirectResponse
    {
        return $this->registerAction->execute($request);
    }
}
