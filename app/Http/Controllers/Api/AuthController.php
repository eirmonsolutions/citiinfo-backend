<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthProfileService $authProfile
    ) {}

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:6'],
                'agree_terms' => ['accepted'],
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
                'is_auto_created' => false,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            $profile = $this->authProfile->cacheToken($user, $token);

            return response()->json([
                'ok' => true,
                'authenticated' => true,
                'message' => 'Registration successful.',
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $profile,
                'redirect_to' => '/user/dashboard',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string', 'min:6'],
            ]);

            if (! Auth::attempt($validated)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Invalid email or password.',
                ], 401);
            }

            $user = Auth::user();

            if (! empty($user->is_blocked)) {
                Auth::logout();

                return response()->json([
                    'ok' => false,
                    'message' => 'Your account is blocked.',
                ], 403);
            }

            $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;
            $profile = $this->authProfile->cacheToken($user, $token);

            $role = $user->role ?? 'user';

            $redirectTo = match ($role) {
                'superadmin' => '/superadmin/dashboard',
                'admin' => '/admin/dashboard',
                'seo_user', 'site_user', 'blog_user' => '/seo-user/dashboard',
                default => '/user/dashboard',
            };

            return response()->json([
                'ok' => true,
                'authenticated' => true,
                'message' => 'Login successful.',
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $profile,
                'redirect_to' => $redirectTo,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function profile(Request $request)
    {
        $user = Auth::guard('web')->user()
            ?? auth('sanctum')->user()
            ?? $request->user();

        if (! $user) {
            return response()->json([
                'ok' => true,
                'authenticated' => false,
                'user' => null,
            ]);
        }

        if (! empty($user->is_blocked)) {
            return response()->json([
                'ok' => false,
                'authenticated' => false,
                'message' => 'Your account is blocked.',
                'user' => null,
            ], 403);
        }

        $profile = $this->authProfile->getProfileFromCache($user->id)
            ?? $this->authProfile->refreshProfileCache($user);

        return response()->json([
            'ok' => true,
            'authenticated' => true,
            'user' => $profile,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $token = $request->bearerToken();

        $this->authProfile->clearUserCache($user->id, $token);
        $user->currentAccessToken()->delete();

        return response()->json([
            'ok' => true,
            'authenticated' => false,
            'message' => 'Logout successful.',
        ]);
    }
}
