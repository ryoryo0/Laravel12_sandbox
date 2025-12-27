<?php

use Illuminate\Support\Facades\Route;

/**
 * Admin
 */
Route::prefix('admin')->name('admin.')->middleware(['guest:admin'])->group(function () {
    
});

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    /**
     *
     * User (管理者管理)
     */
    Route::prefix('/user')->name('user.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
    });

    /**
     *
     * Product
     */
    Route::prefix('/product')->name('product.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('store');
        Route::get('/show/{product}', [App\Http\Controllers\Admin\ProductController::class, 'show'])->name('show');
        Route::get('/edit/{product}', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('edit');
        Route::put('/update/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('update');
        Route::delete('/destroy/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('destroy');
        Route::get('/image/{ulid}', [App\Http\Controllers\Admin\ProductController::class, 'image'])->name('imageShow');
    });

    /**
     *
     * Product Variant (在庫管理)
     */
    Route::prefix('/product-variant')->name('product-variant.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProductVariantController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\ProductVariantController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Admin\ProductVariantController::class, 'store'])->name('store');
        Route::get('/show/{productVariant}', [App\Http\Controllers\Admin\ProductVariantController::class, 'show'])->name('show');
        Route::get('/edit/{productVariant}', [App\Http\Controllers\Admin\ProductVariantController::class, 'edit'])->name('edit');
        Route::put('/update/{productVariant}', [App\Http\Controllers\Admin\ProductVariantController::class, 'update'])->name('update');
        Route::delete('/destroy/{productVariant}', [App\Http\Controllers\Admin\ProductVariantController::class, 'destroy'])->name('destroy');
    });

    /**
     *
     * Event (イベント管理)
     */
    Route::prefix('/event')->name('event.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\EventController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Admin\EventController::class, 'store'])->name('store');
        Route::get('/show/{event}', [App\Http\Controllers\Admin\EventController::class, 'show'])->name('show');
        Route::get('/edit/{event}', [App\Http\Controllers\Admin\EventController::class, 'edit'])->name('edit');
        Route::put('/update/{event}', [App\Http\Controllers\Admin\EventController::class, 'update'])->name('update');
        Route::delete('/destroy/{event}', [App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('destroy');
        Route::get('/image/{ulid}', [App\Http\Controllers\Admin\EventController::class, 'image'])->name('imageShow');
    });

    /**
     *
     * Category (カテゴリー管理)
     */
    Route::prefix('/category')->name('category.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('index');
        Route::post('/store', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('store');
        Route::get('/show/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('show');
        Route::put('/update/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('update');
        Route::delete('/destroy/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('destroy');
    });

    /**
     *
     * User Invitation (ユーザー招待)
     */
    Route::prefix('/user-invitation')->name('user-invitation.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserInvitationController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\UserInvitationController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Admin\UserInvitationController::class, 'store'])->name('store');
    });

    Route::prefix('/temporary')->name('temporary.')->group(function () {
        Route::post('/upload', App\Http\Controllers\Admin\TemporaryController::class)->name('upload');
        Route::get('/image/{ulid}', [App\Http\Controllers\Admin\TemporaryController::class, 'show'])->name('image');
    });
});