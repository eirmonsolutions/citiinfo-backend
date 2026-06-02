<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ManagesBlog;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BlogManageApiController extends Controller
{
    use ManagesBlog;

    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = min((int) $request->get('per_page', 15), 50);

        $query = Blog::with('user:id,name,email')->latest();

        if ($user->isSeoUser()) {
            $query->where('user_id', $user->id);
        } elseif ($user->role !== 'superadmin') {
            return response()->json(['ok' => false, 'message' => 'Forbidden.'], 403);
        }

        if ($user->role === 'superadmin' && $request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        $blogs = $query->paginate($perPage);

        return response()->json([
            'ok'   => true,
            'data' => $blogs->getCollection()->map->toApiArray(true)->values(),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page'    => $blogs->lastPage(),
                'per_page'     => $blogs->perPage(),
                'total'        => $blogs->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->canManageBlogs()) {
            return response()->json(['ok' => false, 'message' => 'Forbidden.'], 403);
        }

        try {
            $request->validate($this->blogRules());

            $blog = new Blog();
            $this->saveBlog($blog, $request, (int) $request->user()->id);
            $blog->load('user:id,name,email');

            return response()->json([
                'ok'      => true,
                'message' => 'Blog created successfully.',
                'data'    => $blog->toApiArray(true),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'ok'      => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    public function show(Request $request, Blog $blog)
    {
        if (!$this->canView($request->user(), $blog)) {
            return response()->json(['ok' => false, 'message' => 'Forbidden.'], 403);
        }

        $blog->load('user:id,name,email');

        return response()->json([
            'ok'   => true,
            'data' => $blog->toApiArray(true),
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        if (!$this->canModify($request->user(), $blog)) {
            return response()->json(['ok' => false, 'message' => 'Forbidden.'], 403);
        }

        try {
            $request->validate($this->blogRules($blog));

            $ownerId = $request->user()->role === 'superadmin'
                ? (int) $blog->user_id
                : (int) $request->user()->id;

            $this->saveBlog($blog, $request, $ownerId);
            $blog->load('user:id,name,email');

            return response()->json([
                'ok'      => true,
                'message' => 'Blog updated successfully.',
                'data'    => $blog->fresh()->toApiArray(true),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'ok'      => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    public function destroy(Request $request, Blog $blog)
    {
        if (!$this->canDelete($request->user(), $blog)) {
            return response()->json(['ok' => false, 'message' => 'Forbidden.'], 403);
        }

        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        if ($blog->meta_image) {
            Storage::disk('public')->delete($blog->meta_image);
        }

        $blog->delete();

        return response()->json([
            'ok'      => true,
            'message' => 'Blog deleted successfully.',
        ]);
    }

    protected function ownsBlog($user, Blog $blog): bool
    {
        return $user->isSeoUser() && (int) $blog->user_id === (int) $user->id;
    }

    protected function canView($user, Blog $blog): bool
    {
        if ($user->role === 'superadmin') {
            return true;
        }

        return $this->ownsBlog($user, $blog);
    }

    protected function canModify($user, Blog $blog): bool
    {
        if ($user->role === 'superadmin') {
            return true;
        }

        return $this->ownsBlog($user, $blog);
    }

    protected function canDelete($user, Blog $blog): bool
    {
        if ($user->role === 'superadmin') {
            return true;
        }

        return $this->ownsBlog($user, $blog);
    }
}
