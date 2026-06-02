<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;

class BlogPageController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('is_published', true)
            ->latest()
            ->paginate(12);

        return view('pages.blogpage', compact('blogs'));
    }

    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('pages.blog-show', compact('blog'));
    }
}
