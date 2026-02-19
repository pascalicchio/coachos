<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SimpleLoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        // Try to authenticate
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json([
                'success' => true,
                'user' => Auth::user()
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }
}
