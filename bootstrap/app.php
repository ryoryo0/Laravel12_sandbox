<?php

use App\Http\Middleware\RedirectIfCustomerAuthenticated;
use App\Http\Middleware\RedirectIfCustomerNotAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))


    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        // 非ログインユーザー用のリダイレクト設定
        $middleware->alias([
            'guest.customer' => RedirectIfCustomerAuthenticated::class,
            'auth.customer' => RedirectIfCustomerNotAuthenticated::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    ->create();