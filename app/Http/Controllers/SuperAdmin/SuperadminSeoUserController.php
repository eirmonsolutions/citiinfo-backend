<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperadminSeoUserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['seo_user', 'site_user', 'blog_user'])
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('superadmin.seo-user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => 'seo_user',
            'is_blocked'        => 0,
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'SEO user created. They can log in and add blogs only.');
    }

    public function toggleStatus(User $user)
    {
        abort_unless($user->isSeoUser(), 404);

        $user->is_blocked = !$user->is_blocked;
        $user->save();

        return back()->with(
            'success',
            $user->is_blocked ? 'SEO user blocked.' : 'SEO user activated.'
        );
    }

    public function destroy(User $user)
    {
        abort_unless($user->isSeoUser(), 404);

        $user->delete();

        return back()->with('success', 'SEO user deleted.');
    }
}
