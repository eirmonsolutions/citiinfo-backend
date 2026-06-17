<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function homeCategories()
    {
        $categories = Category::where('is_active', 1)
            ->where('is_home', 1)
            ->withCount([
                'businessListings as listings_count' => function ($q) {
                    $q->where('is_allowed', 1)
                        ->where('status', 'published')
                        ->whereNull('deleted_at');
                }
            ])
            ->orderBy('id', 'desc')
            ->take(12)
            ->get([
                'id',
                'name',
                'slug',
                'icon',
                'categoryimage',
            ]);

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
