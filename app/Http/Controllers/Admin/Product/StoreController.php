<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Requests\Admin\Product\StoreRequest;
use App\Models\Product;
use App\Models\TemporaryImage;
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
            $validated = $request->validated();
            $validated['create_admin_id'] = Auth::user()->id;
            $validated['ulid'] = Str::ulid();

            $thumbImage = TemporaryImage::query()
                ->where('ulid', $validated['thumbnail'])
                ->first()
                ->toArray();
            $thumbImage['is_thumbnail'] = true;

            $otherImages = TemporaryImage::query()
                ->whereIn('ulid', $validated['other_thumbnail'])
                ->get()
                ->toArray();
        
            foreach ($otherImages as $image) {
                $image['is_thumbnail'] = false;
            }

            DB::transaction(function () use ($validated, $thumbImage, $otherImages) {
                // 商品登録
                $product = Product::create($validated);
                // 商品カテゴリー登録
                $product->categories()->sync($validated['category_ids']);
                // 商品の画像登録
                $product->images()->create($thumbImage);
                $product->images()->createMany($otherImages);
                // 登録　完了のログ
                Log::info('product create', ['product_id' => $product->id]);
            });
            return redirect()->route('admin.product.index')->with('success', '商品の登録が完了しました');
        } catch (Throwable $e)  {
            Log::error($e);
            return redirect()->back()->with('error', '商品の登録に失敗しました');
        }
    }   
}
