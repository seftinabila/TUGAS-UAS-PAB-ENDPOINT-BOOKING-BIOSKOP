<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna yang terautentikasi adalah admin
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request);
        }

        // Jika bukan admin, kembalikan respons Unauthorized
        return response()->json(['message' => 'Anda tidak memiliki akses membuka halaman ini'], 403);
    }
}

