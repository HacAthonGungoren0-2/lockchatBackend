<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\API\BaseController as BaseController;

class LoginController extends BaseController
{
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $credentials = compact('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $accessToken = $user->createToken('MyApp')->accessToken;
            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully.',
                'user_id' => $user->id,
                'access_token' => $accessToken,
            ], 200);
        }

        return response()->json([
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'error',
            'message' => 'Invalid credentials.',
        ], 401);
    }

    public function logout(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);

        if ($user) {
            $user->token()->revoke();

            return response()->json([
                'status' => 'success',
                'message' => 'User logged out successfully.',
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User is not authenticated or ID is invalid.',
        ], 401);
    }
}