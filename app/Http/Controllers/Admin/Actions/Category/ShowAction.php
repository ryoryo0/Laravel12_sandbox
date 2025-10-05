<?php

namespace App\Http\Controllers\Admin\Actions\Category;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShowAction
{
    public function execute(Category $category): JsonResponse
    {
        $adminUser = Auth::user();

        // 権限チェック：現在のadminユーザーが作成したカテゴリーかをチェック
        if ($category->create_admin_id !== $adminUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'このカテゴリーにアクセスする権限がありません',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'category' => $category,
        ]);
    }
}
