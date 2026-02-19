
<?php

use Illuminate\Http\Request;

Route::post('/login', function (Request $request) {
    if ($request->email === 'demo@gymmanageros.com' && $request->password === 'password') {
        $request->session()->put('user_id', 1);
        return response()->json(['success' => true, 'redirect' => '/dashboard']);
    }
    return response()->json(['success' => false], 401);
});

