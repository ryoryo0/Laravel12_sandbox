<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;

class RedirectIfCustomerAuthenticated extends RedirectIfAuthenticated
{
    /**
     * 認証済みの場合はdashboardにリダイレクト
     */
    protected function defaultRedirectUri(): string
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }
        return route('top');
    }
}
