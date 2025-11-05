<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        $admin = User::where('email', $request->email)->first();
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $admin->tokens()->delete();
        $token = $admin->createToken('admin-token', ['*'])->plainTextToken;
        return ApiResponse::success(['admin' => $admin, 'token' => $token], 'Login successful');
        // return response()->json(['admin' => $admin, 'token' => $token]);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
    public function me(Request $request)
    {
        $admin = $request->user();
        return response()->json([
            'role' => $admin->roles->pluck('name')->first(),
            'permissions' => $admin->getAllPermissions()->pluck('name'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
