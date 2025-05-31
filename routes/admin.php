<?php

use Illuminate\Support\Facades\Route;

/**
 * Admin
 */
Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {
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
    Route::get('/dashboard', function () {return view('admin.dashboard');})->name('dashboard');
    Route::post('logout', [App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});