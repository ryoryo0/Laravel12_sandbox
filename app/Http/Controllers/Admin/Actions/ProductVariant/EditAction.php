<?php

namespace App\Http\Controllers\Admin\Actions\ProductVariant;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EditAction
{
    public function execute(ProductVariant $productVariant): View
    {
        $adminUser = Auth::user();
        $products = $adminUser->products()->pluck('name', 'id');

        return view('admin.product-variant.edit')
            ->with([
                'products' => $products,
                'variant' => $productVariant,
            ]);
    }
}
