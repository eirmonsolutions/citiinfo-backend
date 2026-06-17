<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\BusinessListing;
use Illuminate\Http\Request;

class CategoryPageController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $sort = $request->get('sort', 'name_asc');

        $query = Category::where('is_active', 1)
            ->select('id', 'name', 'slug', 'image', 'categoryimage', 'created_at')
            ->withCount([
                'businessListings as listings_count' => function ($q) {
                    $q->where('is_allowed', 1)
                        ->where('status', 'published')
                        ->whereNull('deleted_at');
                }
            ]);

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }

        match ($sort) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'date_asc' => $query->orderBy('created_at', 'asc'),
            'date_desc' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('name', 'asc'),
        };

        $categories = $query->paginate(30);

        return response()->json([
            'success' => true,
            'data' => $categories->getCollection()->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'categoryimage_url' => \storage_public_url($cat->categoryimage),
                'image_url' => \storage_public_url($cat->image),
                'listings_count' => $cat->listings_count,
            ]),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'total' => $categories->total(),
            ],
        ]);
    }

    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $q = trim($request->get('q', ''));
        $sort = $request->get('sort', 'newest');

        $query = BusinessListing::where('category_id', $category->id)
            ->where('is_allowed', 1)
            ->where('status', 'published')
            ->whereNull('deleted_at');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('business_name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        match ($sort) {
            'oldest' => $query->orderBy('created_at', 'asc'),
            'name_asc' => $query->orderBy('business_name', 'asc'),
            'name_desc' => $query->orderBy('business_name', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $listings = $query->paginate(30);

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
            'data' => $listings->getCollection()->map(fn($listing) => [
                'id' => $listing->id,
                'business_name' => $listing->business_name,
                'slug' => $listing->slug,
                'phone' => $listing->phone,
                'whatsapp' => $listing->whatsapp,
                'address' => $listing->address,
                'image_url' => \storage_public_url($listing->image),
                'description' => $listing->description,
            ]),
            'pagination' => [
                'current_page' => $listings->currentPage(),
                'last_page' => $listings->lastPage(),
                'total' => $listings->total(),
            ],
        ]);
    }
}
