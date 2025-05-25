<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfCustomerNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // dd(Auth::guard('customer')->check());
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login');
        }
        return $next($request);
    }
}
