<?php

namespace App\Http\Controllers\Admin\Actions\Category;

use App\Http\Requests\Admin\Category\StoreRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreAction
{
    public function execute(StoreRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['create_admin_id'] = Auth::user()->id;

            $category = Category::create($validated);

            Log::info('category created', ['category_id' => $category->id]);

            return response()->json([
                'success' => true,
                'message' => 'カテゴリーを作成しました',
                'category' => $category,
            ], 201);
        } catch (Throwable $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'カテゴリーの作成に失敗しました',
            ], 500);
        }
    }
}
