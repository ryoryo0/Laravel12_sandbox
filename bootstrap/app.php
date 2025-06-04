<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))


    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        
    )

    ->withMiddleware(function (Middleware $middleware) {
        // 未認証ユーザーのリダイレクト　先を制御
        $middleware->redirectGuestsTo(function (Request $request) {
            return match (true) {
                $request->is('admin/*')    => route('admin.login'),
                $request->is('customer/*') => route('customer.login'),
            };
        });
    
        // 認証ユーザーのリダイレクト先を制御
        $middleware->redirectUsersTo(function (Request $request) {
            return match (true) {
                $request->is('admin/*')    => route('admin.home'),
                $request->is('customer/*') => route('customer.home'),
            };
        });
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    ->create();