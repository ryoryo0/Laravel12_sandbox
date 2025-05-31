<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('top');

require __DIR__.'/admin.php';
require __DIR__.'/customer.php';