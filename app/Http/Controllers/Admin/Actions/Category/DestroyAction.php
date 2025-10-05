<?php

namespace App\Http\Controllers\Admin\Actions\Category;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class DestroyAction
{
    public function execute(Category $category): JsonResponse
    {
        try {
            $adminUser = Auth::user();

            // 権限チェック：現在のadminユーザーが作成したカテゴリーかをチェック
            if ($category->create_admin_id !== $adminUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'このカテゴリーを削除する権限がありません',
                ], 403);
            }

            // 商品との紐付けをチェック
            if ($category->products()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'このカテゴリーは商品に使用されているため削除できません',
                ], 400);
            }

            $categoryName = $category->name;
            $category->delete();

            Log::info('category deleted', ['category_id' => $category->id, 'admin_id' => $adminUser->id]);

            return response()->json([
                'success' => true,
                'message' => "カテゴリー「{$categoryName}」を削除しました",
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'カテゴリーの削除に失敗しました',
            ], 500);
        }
    }
}
