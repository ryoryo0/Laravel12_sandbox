<?php

namespace App\Http\Controllers\Admin\Actions\ProductVariant;

use App\Http\Requests\Admin\ProductVariant\UpdateRequest;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateAction
{
    public function execute(UpdateRequest $request, ProductVariant $productVariant)
    {
        try {
            $validated = $request->validated();

            DB::transaction(function () use ($validated, $productVariant) {
                $productVariant->update($validated);
            });

            return redirect()->route('admin.product-variant.index')->with('success', '在庫の更新が完了しました');
        } catch (Throwable $e) {
            Log::error($e);
            return redirect()->back()->with('error', '在庫の更新に失敗しました');
        }
    }
}
