<?php

namespace App\Http\Controllers\Admin\Actions\ProductVariant;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DestroyAction
{
    public function execute(ProductVariant $productVariant)
    {
        try {
            DB::transaction(function () use ($productVariant) {
                $productVariant->delete();
                Log::info('ProductVariant deleted', ['variant_id' => $productVariant->id]);
            });

            return redirect()->route('admin.product-variant.index')->with('success', '在庫を削除しました。');
        } catch (Throwable $e) {
            Log::error('ProductVariant deletion failed', ['error' => $e->getMessage(), 'variant_id' => $productVariant->id]);
            return redirect()->route('admin.product-variant.index')->with('error', '在庫の削除に失敗しました。');
        }
    }
}
