<?php

namespace App\Http\Controllers\Admin\Actions\ProductVariant;

use App\Http\Requests\Admin\ProductVariant\StoreRequest;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreAction
{
    public function execute(StoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $productId = $validated['product_id'];
            $variants = $validated['variants'];

            DB::transaction(function () use ($productId, $variants) {
                foreach ($variants as $variantData) {
                    ProductVariant::create([
                        'product_id' => $productId,
                        'color' => $variantData['color'],
                        'size' => $variantData['size'],
                        'stock' => $variantData['stock'],
                    ]);
                }
            });

            $count = count($variants);
            return redirect()->route('admin.product-variant.index')->with('success', "{$count}件の在庫登録が完了しました");
        } catch (Throwable $e) {
            Log::error($e);
            return redirect()->back()->with('error', '在庫の登録に失敗しました');
        }
    }
}
