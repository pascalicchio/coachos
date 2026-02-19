<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Dashboard (protected)
Route::get('/dashboard', [DashboardController::class, 'stats'])->name('dashboard');

// Auth routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// API login (for SPA/JS clients)
Route::post('/api/login', function (Request $request) {
    if ($request->email === 'demo@gymmanageros.com' && $request->password === 'password') {
        $request->session()->put('user_id', 1);
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 401);
});
