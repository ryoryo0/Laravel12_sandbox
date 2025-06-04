<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front.index');
})->name('top');

require __DIR__.'/admin.php';
require __DIR__.'/customer.php';