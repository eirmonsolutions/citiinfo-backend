<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class BlogUserApiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->get('per_page', 15), 50);

        $users = User::whereIn('role', ['seo_user', 'site_user', 'blog_user'])
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json([
            'ok'   => true,
            'data' => $users->getCollection()->map(fn (User $u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'role'       => $u->role,
                'is_blocked' => (bool) $u->is_blocked,
                'created_at' => $u->created_at?->toIso8601String(),
            ])->values(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'per_page'     => $users->perPage(),
                'total'        => $users->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            $user = User::create([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'password'          => Hash::make($validated['password']),
                'role'              => 'seo_user',
                'is_blocked'        => 0,
                'email_verified_at' => now(),
            ]);

            return response()->json([
                'ok'      => true,
                'message' => 'Blog user created successfully.',
                'data'    => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'ok'      => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        }
    }
}
