<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front.index');
})->name('top');

/**
 * User Registration (招待ベースのユーザー登録)
 */
Route::get('/register', [App\Http\Controllers\UserRegistrationController::class, 'showRegistrationForm'])->name('user.register.form');
Route::post('/register', [App\Http\Controllers\UserRegistrationController::class, 'register'])->name('user.register');

require __DIR__.'/admin.php';
require __DIR__.'/customer.php';