<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EditController extends Controller
{
    public function __invoke(Request $request)
    {
        $adminUser = Auth::user();
        $categories = $adminUser->categories()->pluck('name', 'id');
        $product = Product::where('id', $request->id)->with('categories', 'images')->first();

        // detail_json内の画像パスをassetパスに変換
        if ($product && $product->detail_json) {
            $product->detail_json = $this->convertImagePathsToAssets($product->detail_json);
        }

        return view('admin.product.edit')
            ->with([
                'categories' => $categories,
                'product' => $product,
            ]);
    }

    /**
     * QuillのJSONデータ内の画像パスをassetのフルパスに変換する
     *
     * @param string $jsonData
     * @return string
     */
    private function convertImagePathsToAssets(string $jsonData): string
    {
        try {
            $data = json_decode($jsonData, true);
            if (!$data || !isset($data['ops'])) {
                return $jsonData;
            }
            foreach ($data['ops'] as &$op) {
                if (isset($op['insert']['image'])) {
                    $imagePath = $op['insert']['image'];
                    // storage/で始まるパスの場合はassetパスに変換
                    if (strpos($imagePath, 'images/') === 0) {
                        $op['insert']['image'] = asset('storage/' . $imagePath);
                        
                    }
                }
            }

            return json_encode($data);
        } catch (\Exception $e) {
            Log::error('QuillのJSONデータ変換エラー: ' . $e->getMessage());
            return $jsonData;
        }
    }
}
