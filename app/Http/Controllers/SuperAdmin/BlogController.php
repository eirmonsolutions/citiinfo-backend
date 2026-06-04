<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Concerns\ManagesBlog;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    use ManagesBlog;

    public function index()
    {
        $blogs = Blog::with('user:id,name,email')
            ->latest('blog_date')
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('superadmin.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('superadmin.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->blogRules());

        $blog = new Blog();

        $this->saveBlog($blog, $request, (int) auth()->id());

        return redirect()
            ->route('superadmin.blog.index')
            ->with('success', 'Blog created successfully.');
    }

    public function edit(Blog $blog)
    {
        return view('superadmin.blog.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate($this->blogRules($blog));

        $this->saveBlog($blog, $request, (int) $blog->user_id);

        return redirect()
            ->route('superadmin.blog.index')
            ->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        if ($blog->meta_image) {
            Storage::disk('public')->delete($blog->meta_image);
        }

        $blog->delete();

        return back()->with('success', 'Blog deleted successfully.');
    }
}
