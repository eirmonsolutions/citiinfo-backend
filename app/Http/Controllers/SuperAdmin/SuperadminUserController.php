<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
class SuperadminUserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['user', 'admin'])
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('superadmin.user.index', compact('users'));
    }

    public function toggleStatus(User $user)
    {
        abort_unless($user->isListingUser(), 404);

        $user->is_blocked = !$user->is_blocked;
        $user->save();

        return back()->with(
            'success',
            $user->is_blocked ? 'User blocked successfully!' : 'User activated successfully!'
        );
    }

    public function destroy(User $user)
    {
        abort_unless($user->isListingUser(), 404);

        $user->delete();

        return back()->with('success', 'Listing user deleted successfully!');
    }
}

