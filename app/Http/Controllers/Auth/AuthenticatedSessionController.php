<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Hardcoded auth for demo (no DB required)
        if ($request->email === 'demo@gymmanageros.com' && $request->password === 'password') {
            $request->session()->put('user_id', 1);
            $request->session()->put('email', $request->email);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard', absolute: false));
        }
        
        // Fallback to normal auth if DB is available
        try {
            $request->authenticate();
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            // If DB auth fails, use demo
            return back()->withErrors(['email' => 'Invalid credentials. Try demo@gymmanageros.com / password']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
