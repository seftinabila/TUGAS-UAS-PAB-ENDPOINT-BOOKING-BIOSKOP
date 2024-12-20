<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized access. Please login.'], 401);
        }

        // Periksa apakah pengguna memiliki peran 'user'
        if (Auth::user()->role !== 'user') {
            return response()->json(['message' => 'Anda tidak memiliki akses membuka halaman ini'], 403);
        }

        return $next($request);
    }
}

