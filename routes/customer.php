<?php

use Illuminate\Support\Facades\Route;

/**
 * Customer
 */
Route::prefix('customer')->name('customer.')->middleware(['guest:customer'])->group(function () {
    Route::get('login', [App\Http\Controllers\Customer\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [App\Http\Controllers\Customer\Auth\AuthenticatedSessionController::class, 'store']);
    Route::get('register', [App\Http\Controllers\Customer\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [App\Http\Controllers\Customer\Auth\RegisteredUserController::class, 'store']);
    Route::get('forgot-password', [App\Http\Controllers\Customer\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [App\Http\Controllers\Customer\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [App\Http\Controllers\Customer\Auth\NewPasswordController::class, 'create']) ->name('password.reset');
    Route::post('reset-password', [App\Http\Controllers\Customer\Auth\NewPasswordController::class, 'store'])->name('password.store');
});

Route::prefix('customer')->name('customer.')->middleware(['auth:customer'])->group(function () {
    Route::get('verify-email', [App\Http\Controllers\Customer\Auth\EmailVerificationPromptController::class])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [App\Http\Controllers\Customer\Auth\VerifyEmailController::class])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [App\Http\Controllers\Customer\Auth\EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
    Route::get('confirm-password', [App\Http\Controllers\Customer\Auth\ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [App\Http\Controllers\Customer\Auth\ConfirmablePasswordController::class, 'store']);
    Route::put('password', [App\Http\Controllers\Customer\Auth\PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [App\Http\Controllers\Customer\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', function () {return view('customer.dashboard');})->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'destroy'])->name('profile.destroy');
});

