<?php

namespace App\Http\Controllers\Admin\Actions\Category;

use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateAction
{
    public function execute(UpdateRequest $request, Category $category): JsonResponse
    {
        try {
            $adminUser = Auth::user();

            // 権限チェック：現在のadminユーザーが作成したカテゴリーかをチェック
            if ($category->create_admin_id !== $adminUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'このカテゴリーを編集する権限がありません',
                ], 403);
            }

            $validated = $request->validated();
            $category->update($validated);

            Log::info('category updated', ['category_id' => $category->id]);

            return response()->json([
                'success' => true,
                'message' => 'カテゴリーを更新しました',
                'category' => $category,
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'カテゴリーの更新に失敗しました',
            ], 500);
        }
    }
}
