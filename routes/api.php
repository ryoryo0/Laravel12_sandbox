<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Version 1
Route::prefix('v1')->group(function () {

    // 商品関連のエンドポイント
    Route::prefix('products')->group(function () {
        // おすすめ商品一覧
        Route::get('/featured', [ProductController::class, 'featured']);

        // 商品一覧（ページネーション付き）
        Route::get('/', [ProductController::class, 'index']);

        // 商品詳細
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    // 認証が必要なエンドポイント（将来的に追加）
    // Route::middleware('auth:sanctum')->group(function () {
    //     Route::post('/orders', [OrderController::class, 'store']);
    //     Route::get('/orders', [OrderController::class, 'index']);
    // });
});
