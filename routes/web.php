<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::post('/api/login', function (Request $request) {
    if ($request->email === 'demo@gymmanageros.com' && $request->password === 'password') {
        $request->session()->put('user_id', 1);
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 401);
});
