<?php

namespace App\Http\Controllers\Admin\Actions\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CreateAction
{
    public function execute(Request $request): View
    {
        $adminUser = Auth::user();

        // 管理者の全ての商品を取得
        $products = $adminUser->products()
            ->pluck('name', 'id');

        return view('admin.event.create')
            ->with([
                'products' => $products,
            ]);
    }
}
