<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::query()->create([
            'name' => (string) $request->input('name'),
            'email' => (string) $request->input('email'),
            'password' => Hash::make((string) $request->input('password')),
        ]);

        $token = $user->createToken('auth')->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 'Registered', 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::query()
            ->where('email', (string) $request->input('email'))
            ->first();

        if (!$user || !Hash::check((string) $request->input('password'), $user->password)) {
            // پیام باید generic باشد تا لو ندهد ایمیل وجود دارد یا نه
            return ApiResponse::error('Invalid credentials', null, 422);
        }

        $token = $user->createToken('auth')->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 'Logged in');
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return ApiResponse::success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ], 'OK');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return ApiResponse::success(null, 'Logged out');
    }
}
