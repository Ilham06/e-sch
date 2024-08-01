<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        
        $credentials = request(['email', 'password']);
        $user = User::whereEmail($credentials['email'])->with('roles.permissions')->first();
        if (!$user) {
            return response()->json(['error' => 'Email tidak ditemukan'], 401);
        }
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Password salah'], 401);
        }
        // if (!$user->is_active) {
        //     return response()->json(['error' => 'User is not active'], 400);
        // }

        $payload = [
            'iss'  => env('APP_URL'),
            'sub'  => $user->id,
            'data' => $user,
            'iat'  => time(),
            'exp'  => time() + 86400 // 1 day
        ];
        $token = JWT::encode($payload, config('jwt.secret'), config('jwt.algo'));

        // $payloadRefreshToken = $payload;
        // $payloadRefreshToken['exp'] = $payloadRefreshToken['exp'] + (86400 * 14); // 2 week
        // $refreshToken = JWT::encode($payloadRefreshToken, config('jwt.secret'), config('jwt.algo'));

        // return $this->respondWithToken($token, $refreshToken, 86400);

        return response()->json([
            'user' => $user,
            'access_token' => $token,
        ]);
    }
}
