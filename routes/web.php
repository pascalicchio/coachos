<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::post('/login', function () {
    $email = request('email');
    $password = request('password');
    
    if ($email === 'demo@gymmanageros.com' && $password === 'password') {
        return redirect('/dashboard');
    }
    
    return 'Invalid credentials';
});
