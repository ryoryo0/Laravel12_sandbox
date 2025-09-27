<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    public function __invoke(Request $request)
    {
        $adminUser = Auth::user();
        $categories = $adminUser->categories()->pluck('name', 'id');
        
        return view('admin.product.create')
            ->with([
                'categories' => $categories,
            ]);
    }
}
