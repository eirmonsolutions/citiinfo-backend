<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\Api\WishlistApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Auth API (login, register, profile, logout)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/auth/profile', [AuthController::class, 'profile']);
Route::get('/auth-user', [AuthController::class, 'profile']); // backward compatible alias
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/home-categories', [CategoryController::class, 'homeCategories']);
Route::get('/categories', [CategoryPageController::class, 'index']);
Route::get('/categories/{slug}', [CategoryPageController::class, 'show']);
Route::get('/listings', [ListingApiController::class, 'index']);

Route::post('/business-enquiry', [BusinessEnquiryApiController::class, 'store']);
Route::post('/listings/{slug}/view', [ListingViewApiController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/business-reviews', [BusinessReviewApiController::class, 'store']);
    Route::get('/wishlist', [WishlistApiController::class, 'index']);
    Route::get('/wishlist/ids', [WishlistApiController::class, 'ids']);
    Route::post('/wishlist/toggle', [WishlistApiController::class, 'toggle']);
});



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
