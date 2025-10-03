<?php

namespace App\Http\Controllers\Admin\Actions\Product;

use App\Http\Requests\Admin\Product\StoreRequest;
use App\Models\Product;
use App\Models\TemporaryImage;
use App\Services\File\FileTransferService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class StoreAction
{
    public function __construct(
        private FileTransferService $fileTransferService
    ) {}

    public function execute(StoreRequest $request)
    {
        try {
            $validated = $request->validated();

            // quillエディタのjson内部の画像の本登録を行い、画像パスを書き換える
            $validated['detail_json'] = $this->changeQuillImagePath($validated['detail_json']);

            $validated['create_admin_id'] = Auth::user()->id;
            $validated['ulid'] = Str::ulid();

            DB::transaction(function () use ($validated) {
                // 商品登録
                $product = Product::create($validated);
                // 商品カテゴリー登録
                $product->categories()->sync($validated['category_ids']);
                // 商品の画像登録
                if ($validated['thumbnail']) {
                    $thumbImage = TemporaryImage::query()
                        ->where('ulid', $validated['thumbnail'])
                        ->first()
                        ->toArray();
                    $thumbImage['is_thumbnail'] = true;
                    $thumbImage['file_path'] = $this->fileTransferService->copyFileToDirectory($thumbImage['file_path'], 'product');
                    $product->images()->create($thumbImage);
                }

                if ($validated['other_thumbnail']) {
                    $otherImages = TemporaryImage::query()
                        ->whereIn('ulid', $validated['other_thumbnail'])
                        ->get()
                        ->toArray();

                    foreach ($otherImages as &$image) {
                        $image['is_thumbnail'] = false;
                        $image['file_path'] = $this->fileTransferService->copyFileToDirectory($image['file_path'], 'product');
                    }
                    $product->images()->createMany($otherImages);
                }
                // 登録　完了のログ
                Log::info('product create', ['product_id' => $product->id]);
            });
            return redirect()->route('admin.product.index')->with('success', '商品の登録が完了しました');
        } catch (Throwable $e) {
            Log::error($e);
            return redirect()->back()->with('error', '商品の登録に失敗しました');
        }
    }

    /**
     * quillのデータ内の画像パス情報を更新する
     */
    private function changeQuillImagePath($quillData): ?string
    {
        if (!$quillData) return null;
        $ops = json_decode($quillData);

        foreach ($ops as $data) {
            foreach ($data as $item) {
                if (property_exists($item->insert, 'image')) {
                    $relativePath = $this->fileTransferService->copyFileToDirectory($item->insert->image, 'quill');
                    $item->insert->image = $relativePath;
                }
            }
        }
        // 更新されたJSONデータを再度エンコード
        return json_encode($ops);
    }

}