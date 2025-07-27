<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Requests\Admin\Product\StoreRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class StoreController
{
    public function __invoke(StoreRequest $request)
    {
        try {
            $product = new Product();
            $validated = $request->validated();
            $validated['create_admin_id'] = Auth::user()->id;
            $validated['ulid'] = Str::ulid();
            DB::transaction(function () use ($product, $validated) {
                $product->fill($validated)->save();
            });
            Log::info('product create', ['product_id' => $product->id]);
            return redirect()->route('admin.product.index')->with('success', '商品の登録が完了しました');
        } catch (Throwable $e)  {
            Log::error($e);
            return redirect()->back()->with('error', '商品の登録に失敗しました');
        }
    }   
}
