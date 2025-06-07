<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;


class SetSessionTablePerGuard
{
    /**
     * URLによって参照するセッションのテーブルを変更する
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin/*')) {
            Config::set('session.table', 'admin_sessions');
        } 
        
        if ($request->is('customer/*')) {
            Config::set('session.table', 'customer_sessions');
        }

        return $next($request);
    }
}
