<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Customer\Controller;
use App\Http\Requests\Customer\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('customer.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::guard('customer')->attempt($credentials, $request->filled('remember'))) {
            $request->session(['guard' => 'customer'])->regenerate();
            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors([
            'email' => 'ログインに失敗しました。',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session(['guard' => 'customer'])->invalidate();

        $request->session(['guard' => 'customer'])->regenerateToken();

        return redirect()->route('customer.login');
    }
}
