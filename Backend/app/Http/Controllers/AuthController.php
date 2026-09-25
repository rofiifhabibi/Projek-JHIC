<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'identity' => ['required', 'string'], // NIS murni atau Email sekolah
            'password' => ['required', 'string'],
        ]);

        // Cek apakah identity berupa email atau username (NIS/NIP)
        $field = filter_var($request->identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($field, $request->identity)->first();

        // Validasi kecocokan password hash
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'NIS/Email atau kata sandi tidak valid.',
            ], 401);
        }

        // Generate Token Sanctum untuk client Vue.js
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'user' => [
                    'user_id' => $user->user_id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'role' => $user->role, // 'student', 'teacher', 'bk', 'satpam'
                    'class_name' => $user->class_name,
                ],
            ],
        ], 200);
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        // Hapus token aktif saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil keluar (Token dicabut).',
        ]);
    }
}