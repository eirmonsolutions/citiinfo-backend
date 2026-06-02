<?php

namespace App\Http\Controllers\BlogUser;

use App\Http\Controllers\Controller;
use App\Models\Blog;

class DashboardController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('user_id', auth()->id())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('blog-user.dashboard', compact('blogs'));
    }
}
