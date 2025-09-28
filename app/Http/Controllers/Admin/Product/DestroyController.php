<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DestroyController
{
    public function __invoke(int $id)
    {
        try {
            $adminUser = Auth::user();

            // 商品が存在し、かつ現在のadminユーザーが作成した商品かをチェック
            $product = Product::where('id', $id)
                ->where('create_admin_id', $adminUser->id)
                ->first();

            if (!$product) {
                return redirect()->route('admin.product.index')->with('error', '削除権限がないか、商品が存在しません。');
            }

            DB::transaction(function () use ($product) {
                // 商品に関連する画像ファイルを削除
                $images = $product->images;
                foreach ($images as $image) {
                    if ($image->file_path && Storage::disk('public')->exists($image->file_path)) {
                        Storage::disk('public')->delete($image->file_path);
                    }
                }

                // 商品の画像データを削除
                $product->images()->delete();

                // 商品カテゴリーの関連を削除
                $product->categories()->detach();

                // 商品を削除
                $product->delete();

                Log::info('Product deleted', ['product_id' => $product->id, 'admin_id' => Auth::user()->id]);
            });

            return redirect()->route('admin.product.index')->with('success', '商品「' . $product->name . '」を削除しました。');
        } catch (Throwable $e) {
            Log::error('Product deletion failed', ['error' => $e->getMessage(), 'product_id' => $id]);
            return redirect()->route('admin.product.index')->with('error', '商品の削除に失敗しました。');
        }
    }
}