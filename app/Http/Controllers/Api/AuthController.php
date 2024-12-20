<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        // Validasi inputan
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // Memastikan email unik
            'password' => ['required', 'confirmed', 'min:8'], // Validasi password
            'role' => ['required', 'in:admin,user'], // Validasi role (optional, bisa Anda sesuaikan)
        ]);

        // Menyimpan pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user', // Default role 'user'
            'remember_token' => Str::random(10),
        ]);

        // Event setelah pengguna berhasil didaftarkan
        event(new Registered($user));

        // Buat token untuk API autentikasi
        $token = $user->createToken('auth_token')->plainTextToken;

        // Simpan token pada kolom remember_token
        $user->update(['plain_token' => $token]);

        // Mengembalikan respons dengan data pengguna dan token
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        // Validasi inputan
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Mengecek kredensial pengguna
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Membuat token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Mengembalikan response dengan token
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        // Menghapus token yang digunakan
        $request->user()->currentAccessToken()->delete();

        // Mengembalikan response logout
        return response()->json([
            'message' => 'Logout successful',
        ]);
        return response()->json(['message' => 'Berhasil'], 200);
    }
}

