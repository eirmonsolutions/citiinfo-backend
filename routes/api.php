<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\BusinessListing;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CategoryPageController;
use App\Http\Controllers\Api\ListingApiController;
use App\Http\Controllers\Api\BusinessEnquiryApiController;
use App\Http\Controllers\Api\BusinessReviewApiController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\BlogManageApiController;
use App\Http\Controllers\Api\BlogUserApiController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ListingViewApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/auth-user', function (Request $request) {
    $user = auth('sanctum')->user();

    if (!$user) {
        return response()->json([
            'authenticated' => false,
            'user' => null,
        ]);
    }

    $role = $user->role ?? 'user';

    $displayName = $user->name ?? 'User';
    $dashboardUrl = url('/user/dashboard');

    if ($role === 'superadmin') {
        $displayName = 'Super Admin';
        $dashboardUrl = url('/superadmin/dashboard');
    }

    if ($role === 'admin') {
        $businessUserId = $user->business_user_id ?? $user->id;

        $businessName = BusinessListing::where('user_id', $businessUserId)
            ->latest('id')
            ->value('business_name');

        if (!empty($businessName)) {
            $displayName = $businessName;
        }

        $dashboardUrl = url('/admin/dashboard');
    }

    if (in_array($role, ['seo_user', 'site_user', 'blog_user'], true)) {
        $dashboardUrl = url('/seo-user/dashboard');
    }

    $parts = preg_split('/\s+/', trim($displayName));

    $initials = strtoupper(
        substr($parts[0] ?? 'U', 0, 1) .
            substr($parts[1] ?? '', 0, 1)
    );

    return response()->json([
        'authenticated' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $role,
            'display_name' => $displayName,
            'initials' => $initials,
            'avatar' => $user->avatar ?? null,
            'dashboard_url' => $dashboardUrl,
            'wishlist_count' => 0,
        ],
    ]);
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/home-categories', [CategoryController::class, 'homeCategories']);
Route::get('/categories', [CategoryPageController::class, 'index']);
Route::get('/categories/{slug}', [CategoryPageController::class, 'show']);
Route::get('/listings', [ListingApiController::class, 'index']);

Route::post('/business-enquiry', [BusinessEnquiryApiController::class, 'store']);
Route::post('/listings/{slug}/view', [ListingViewApiController::class, 'store']);
Route::post('/business-reviews', [BusinessReviewApiController::class, 'store']);



Route::get('/form-categories', fn() => \App\Models\Category::where('is_active', 1)->orderBy('name')->get());
Route::get('/form-countries', fn() => \App\Models\Country::orderBy('name')->get());
Route::get('/form-states', fn(\Illuminate\Http\Request $request) => \App\Models\State::where('country_id', $request->country_id)->orderBy('name')->get());
Route::get('/form-cities', fn(\Illuminate\Http\Request $request) => \App\Models\City::where('state_id', $request->state_id)->orderBy('name')->get());
Route::get('/form-features', fn() => \App\Models\Feature::orderBy('name')->get());

Route::post('/submit-listing', [\App\Http\Controllers\ListingController::class, 'store']);
Route::get('/home-cities', [HomeController::class, 'homeCities']);

/*
|--------------------------------------------------------------------------
| Blog API (public)
|--------------------------------------------------------------------------
*/
Route::get('/blogs', [BlogApiController::class, 'index']);
Route::get('/blogs/{slug}', [BlogApiController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+');

/*

|--------------------------------------------------------------------------
| Blog API (authenticated – blog_user & superadmin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:seo_user,site_user,blog_user,superadmin'])->group(function () {
    Route::get('/manage/blogs', [BlogManageApiController::class, 'index']);
    Route::get('/manage/blogs/{blog}', [BlogManageApiController::class, 'show']);
    Route::post('/manage/blogs', [BlogManageApiController::class, 'store']);
    Route::put('/manage/blogs/{blog}', [BlogManageApiController::class, 'update']);
    Route::patch('/manage/blogs/{blog}', [BlogManageApiController::class, 'update']);
    Route::delete('/manage/blogs/{blog}', [BlogManageApiController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/seo-users', [BlogUserApiController::class, 'index']);
    Route::post('/superadmin/seo-users', [BlogUserApiController::class, 'store']);
    Route::get('/superadmin/blog-users', [BlogUserApiController::class, 'index']);
    Route::post('/superadmin/blog-users', [BlogUserApiController::class, 'store']);
});

Route::post('/contact', [ContactController::class, 'store']);
