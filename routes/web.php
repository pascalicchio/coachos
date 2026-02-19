<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

Route::post('/login', function (Request $request) {
    if ($request->email === 'demo@gymmanageros.com' && $request->password === 'password') {
        session(['logged_in' => true]);
        return redirect('/dashboard');
    }
    return back()->withErrors(['email' => 'Invalid credentials']);
});
