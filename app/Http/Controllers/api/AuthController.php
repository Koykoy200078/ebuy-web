<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('JWT', ['except' => ['login', 'register', 'googleRedirect', 'googleCallback']]);
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
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'errors' => ['email' => 'These credentials do not match our records.']
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$user->email_verified_at) {
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
            );

            Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

            return response()->json([
                'success' => false,
                'message' => 'Email not verified',
                'errors' => [
                    'email' => 'Please verify your email first, we have sent you a verification email',
                    'verification_url' => $verificationUrl
                ]
            ], Response::HTTP_FORBIDDEN);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'errors' => ['email' => 'These credentials do not match our records.']
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 1440,
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


    // Social Login
    public function googleRedirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleCallback(Request $request)
    {
        try {
            $userdata = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch user data from Google'], 400);
        }

        $validator = Validator::make(['email' => $userdata->email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::where('email', $userdata->email)->where('auth_type', 'google')->first();

        if ($user) {
            $token = JWTAuth::fromUser($user);
            return response()->json(['token' => $token], 200);
        }

        $newUser = $this->createNewUser($userdata);

        $token = JWTAuth::fromUser($newUser);
        return response()->json(['token' => $token], 200);
    }

    private function createNewUser($userdata)
    {
        $uuid = Str::uuid()->toString();

        $user = new User();
        $user->name = $userdata->name;
        $user->email = $userdata->email;
        $user->password = Hash::make($uuid . now());
        $user->auth_type = 'google';
        $user->email_verified_at = now();
        $user->save();

        return $user;
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
