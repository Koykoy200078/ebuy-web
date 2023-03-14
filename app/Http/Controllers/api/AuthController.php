<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('JWT', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->only('email', 'password'), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$token = auth()->guard('api')->attempt($validator->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'errors' => ['email' => 'These credentials do not match our records.']
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 120,
            'user' => auth()->guard('api')->user()
        ])->header('Content-Type', 'application/json');
    }


    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->only('name', 'email', 'password'), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please check your input.',
                'errors' => $validator->errors()
            ], 422)->header('Content-Type', 'application/json');
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User successfully registered',
            'user' => $user
        ], 201)->header('Content-Type', 'application/json');
    }





    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found. Please login.',
                'error' => 'Unauthorized'
            ], 401)->header('Content-Type', 'application/json');
        }

        return response()->json([
            'success' => true,
            'message' => 'User found',
            'user' => $user
        ], 200)->header('Content-Type', 'application/json');
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->guard('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'User successfully logged out',
        ], 200)->header('Content-Type', 'application/json');
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to refresh token',
                'error' => $e->getMessage()
            ], 401)->header('Content-Type', 'application/json');
        }

        return response()->json([
            'success' => true,
            'message' => 'Token successfully refreshed',
            'token' => $newToken
        ], 200)->header('Content-Type', 'application/json');
    }
}
