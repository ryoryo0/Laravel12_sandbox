<?php

namespace App\Http\Controllers\Admin\Actions\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CreateAction
{
    public function execute(Request $request): View
    {
        $adminUser = Auth::user();
        $categories = $adminUser->categories()->pluck('name', 'id');

        return view('admin.product.create')
            ->with([
                'categories' => $categories,
            ]);
    }
}