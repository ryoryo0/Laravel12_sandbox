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
use Illuminate\Support\Facades\Storage;

class StoreController
{
    public function __invoke(StoreRequest $request)
    {
        try {
            $validated = $request->validated();

            // quillエディタのjson内部の画像の本登録を行い、画像パスを書き換える
            $validated['detail_json'] = $this->changeQuillImagePath($validated['detail_json']);

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


    /**
     * quillのデータ内の画像パス情報を更新する
     *
     * @param  string $quillData
     * @return string $ops
     */
    function changeQuillImagePath($quillData): string
    {
        $ops = json_decode($quillData);
        
        foreach ($ops as $data) {
            foreach ($data as $item) {
                if (property_exists($item->insert, 'image')) {
                    // $originalPath = $item->insert->image;
                    $relativePath = $this->copyFileToDirectory($item->insert->image, 'quill');
                    $item->insert->image = $relativePath;
                }
            }
        }
        // 更新されたJSONデータを再度エンコード
        return json_encode($ops);
    }
    
    /**
     * 一時画像を対象ディレクトリへ本登録を実施する
     *
     * @param  string $sourcePath
     * @param  string $targetDir
     * @return ?string
     */
    function copyFileToDirectory(string $sourcePath, string $targetDir): ?string
    {   
        $tempRelativePath = str_replace('/storage/', '', parse_url($sourcePath, PHP_URL_PATH));
        if (!self::isExistsTempFile($tempRelativePath)) {
            return null; // 元ファイルが存在しない場合
        }
    
        // 保存先パス（同じファイル名で保存）
        $fileName   = basename($sourcePath);
        $saveRelativePath = $targetDir . '/' . $fileName;
        if (Storage::disk('public')->copy($tempRelativePath, $saveRelativePath)) {
            return $saveRelativePath;
        }
        return null;
    }

    
    /**
     * 一時画像ディレクトリ内部に対象のファイルが存在するか確認を行う処理
     *
     * @param  mixed $tempRelativePath
     * @return bool
     */
    static function isExistsTempFile (string $tempRelativePath): bool
    {
        $result = Storage::disk('public')->exists($tempRelativePath);

        return $result; 
    } 
}
