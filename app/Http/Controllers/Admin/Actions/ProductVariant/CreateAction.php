<?php

namespace App\Http\Controllers\Admin\Actions\ProductVariant;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CreateAction
{
    public function execute(Request $request): View
    {
        $adminUser = Auth::user();
        $products = $adminUser->products()->pluck('name', 'id');

        return view('admin.product-variant.create')
            ->with([
                'products' => $products,
            ]);
    }
}
