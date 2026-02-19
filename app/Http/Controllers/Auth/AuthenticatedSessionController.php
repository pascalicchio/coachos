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
        // Try DB authentication first
        try {
            $request->authenticate();
            
            // Get user and set org_id in session
            $user = Auth::user();
            $request->session()->put('organization_id', $user->organization_id);
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            // Fallback to hardcoded demo auth
            if ($request->email === 'demo@gymmanageros.com' && $request->password === 'password') {
                // Try to get user from DB
                $user = \App\Models\User::where('email', 'demo@gymmanageros.com')->first();
                if ($user) {
                    $request->session()->put('user_id', $user->id);
                    $request->session()->put('email', $user->email);
                    $request->session()->put('organization_id', $user->organization_id);
                } else {
                    $request->session()->put('user_id', 1);
                    $request->session()->put('email', $request->email);
                    $request->session()->put('organization_id', 1);
                }
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard', absolute: false));
            }
            
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
