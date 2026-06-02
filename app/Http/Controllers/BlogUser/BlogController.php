<?php

namespace App\Http\Controllers\BlogUser;

use App\Http\Controllers\Concerns\ManagesBlog;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use ManagesBlog;

    public function create()
    {
        return view('blog-user.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->blogRules());

        $blog = new Blog();
        $this->saveBlog($blog, $request, (int) auth()->id());

        return redirect()
            ->route('blog.dashboard')
            ->with('success', 'Blog created successfully.');
    }

    public function edit(Blog $blog)
    {
        abort_unless(auth()->user()->isSeoUser() && $blog->user_id === auth()->id(), 403);

        return view('blog-user.blog.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        abort_unless(auth()->user()->isSeoUser() && $blog->user_id === auth()->id(), 403);

        $request->validate($this->blogRules($blog));

        $this->saveBlog($blog, $request, (int) auth()->id());

        return redirect()
            ->route('blog.dashboard')
            ->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        abort_unless(auth()->user()->isSeoUser() && $blog->user_id === auth()->id(), 403);

        if ($blog->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($blog->image);
        }
        if ($blog->meta_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($blog->meta_image);
        }

        $blog->delete();

        return back()->with('success', 'Blog deleted successfully.');
    }
}
