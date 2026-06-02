<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryPageController extends Controller
{
    public function index(Request $request)
    {
        $q    = trim($request->get('q', ''));
        $sort = $request->get('sort', 'name_asc');

        $categoriesQuery = Category::where('is_active', 1)
            ->withCount([
                'businessListings as listings_count' => function ($query) {
                    $query->where('is_allowed', 1)
                        ->where('status', 'published')
                        ->whereNull('deleted_at');
                }
            ]);

        // Search filter
        if ($q !== '') {
            $categoriesQuery->where('name', 'like', '%' . $q . '%');
        }

        // Sorting
        switch ($sort) {
            case 'name_desc':
                $categoriesQuery->orderBy('name', 'desc');
                break;

            case 'date_asc':
                $categoriesQuery->orderBy('created_at', 'asc');
                break;

            case 'date_desc':
                $categoriesQuery->orderBy('created_at', 'desc');
                break;

            case 'name_asc':
            default:
                $categoriesQuery->orderBy('name', 'asc');
                break;
        }

        $categories = $categoriesQuery->paginate(30)->appends($request->query());

        // ✅ AJAX request ke liye JSON return karo
        if ($request->ajax()) {
            $items = $categories->getCollection()->map(function ($category) {
                return [
                    'name' => $category->name,
                    'slug' => Str::slug($category->name),
                    'image_url' => $category->image
                        ? asset('storage/' . $category->image)
                        : asset('assets/images/saloon.jpg'),
                    'categoryimage_url' => !empty($category->categoryimage)
                        ? asset('storage/' . $category->categoryimage)
                        : null,
                    'listings_count' => $category->listings_count,
                ];
            });

            return response()->json([
                'data' => $items,
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'prev_page_url' => $categories->previousPageUrl(),
                'next_page_url' => $categories->nextPageUrl(),
                'total' => $categories->total(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ]);
        }

        $cityNames = ['Melbourne', 'Sydney', 'Perth', 'Brisbane'];

        $cities = City::whereIn('name', $cityNames)
            ->withCount([
                'listings as listings_count' => function ($query) {
                    $query->where('is_allowed', 1)
                        ->where('status', 'published')
                        ->whereNull('deleted_at');
                }
            ])
            ->get()
            ->keyBy('name');

        return view('pages.categorypage', compact('categories', 'cities'));
    }
}