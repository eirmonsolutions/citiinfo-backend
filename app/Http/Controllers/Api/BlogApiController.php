<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->get('per_page', 12), 50);

        $blogs = Blog::where('is_published', true)
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'ok'       => true,
            'data'     => $blogs->getCollection()->map->toApiArray(false)->values(),
            'meta'     => [
                'current_page' => $blogs->currentPage(),
                'last_page'    => $blogs->lastPage(),
                'per_page'     => $blogs->perPage(),
                'total'        => $blogs->total(),
            ],
        ]);
    }

    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (!$blog) {
            return response()->json([
                'ok'      => false,
                'message' => 'Blog not found.',
            ], 404);
        }

        return response()->json([
            'ok'   => true,
            'data' => $blog->toApiArray(true),
        ]);
    }
}
