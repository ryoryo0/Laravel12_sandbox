<?php

use Illuminate\Support\Facades\Route;

/**
 * Admin
 */
Route::prefix('admin')->name('admin.')->middleware(['guest:admin'])->group(function () {
    Route::get('login', [App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'store']);
    Route::get('register', [App\Http\Controllers\Admin\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [App\Http\Controllers\Admin\Auth\RegisteredUserController::class, 'store']);
    Route::get('forgot-password', [App\Http\Controllers\Admin\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [App\Http\Controllers\Admin\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [App\Http\Controllers\Admin\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [App\Http\Controllers\Admin\Auth\NewPasswordController::class, 'store'])->name('password.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('verify-email', [App\Http\Controllers\Admin\Auth\EmailVerificationPromptController::class])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [App\Http\Controllers\Admin\Auth\VerifyEmailController::class])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
    Route::get('confirm-password', [App\Http\Controllers\Admin\Auth\ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [App\Http\Controllers\Admin\Auth\ConfirmablePasswordController::class, 'store']);
    Route::put('password', [App\Http\Controllers\Admin\Auth\PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/home', function () {return view('admin.home');})->name('home');
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     *
     * Product
     */
    Route::prefix('/product')->name('product.')->group(function () {
        Route::get('/', App\Http\Controllers\Admin\Product\IndexController::class)->name('index');
        Route::get('/create', App\Http\Controllers\Admin\Product\CreateController::class)->name('create');
        Route::post('/store', App\Http\Controllers\Admin\Product\StoreController::class)->name('store');
        Route::get('/show/{id}', App\Http\Controllers\Admin\Product\ShowController::class)->name('show');
        Route::get('/edit/{id}', App\Http\Controllers\Admin\Product\EditController::class)->name('edit');
        Route::put('/update/{id}', App\Http\Controllers\Admin\Product\UpdateController::class)->name('update');
        Route::delete('/destroy/{id}', App\Http\Controllers\Admin\Product\DestroyController::class)->name('destroy');
        Route::get('/image/{ulid}', App\Http\Controllers\Admin\Product\ImageController::class)->name('imageShow');
    });

    Route::prefix('/temporary')->name('temporary.')->group(function () {
        Route::post('/upload', App\Http\Controllers\Admin\TemporaryController::class)->name('upload');
        Route::get('/image/{ulid}', [App\Http\Controllers\Admin\TemporaryController::class, 'show'])->name('image');
    });
});