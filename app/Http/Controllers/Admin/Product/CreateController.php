<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke(Request $request)
    {
        $helloWorld = 'Hello World!!';

        return view('admin.product.create')
            ->with([
                'helloWorld' => $helloWorld,
            ]);
    }
}
