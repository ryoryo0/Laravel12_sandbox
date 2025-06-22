<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke(Request $request)
    {
        $categories = ProductCategory::query()->pluck('name', 'id');
        
        return view('admin.product.create')
            ->with([
                'categories' => $categories,
            ]);
    }
}
