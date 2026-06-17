<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingPageController;
use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\CategoryPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\SuperAdmin\CategoryController;
use App\Http\Controllers\SuperAdmin\FeatureController;
use App\Http\Controllers\SuperAdmin\SuperadminListingController;
use App\Http\Controllers\SuperAdmin\SuperadminAddListingController;
use App\Http\Controllers\UserAddListingController;
use App\Http\Controllers\User\MessageController as UserMessageController;
use App\Http\Controllers\ListingController;
use App\Models\Feature;
use App\Models\Category;
use App\Models\Country;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\SuperAdmin\SuperadminUserController;
use App\Http\Controllers\SuperAdmin\SuperadminSeoUserController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AdminListingController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\FrontSearchController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\SuperAdmin\AnalyticsController as SuperadminAnalyticsController;
use App\Http\Controllers\SuperAdmin\SuperadminInboxController;
use App\Http\Controllers\SuperAdmin\SuperadminReviewController;
use App\Http\Controllers\ListingEnquiryController;
use App\Http\Controllers\AjaxLocationController;
use App\Http\Controllers\SuperAdmin\SuperadminDashboardController;
use App\Http\Controllers\SuperAdmin\BlogController;
use App\Http\Controllers\BlogUser\BlogController as BlogUserBlogController;
use App\Http\Controllers\BlogUser\DashboardController as BlogUserDashboardController;
use App\Http\Controllers\Front\BlogPageController;
// use App\Http\Controllers\Auth\GoogleAuthController;

use App\Http\Controllers\BusinessReviewController;

Route::get('/add-listing', [ListingController::class, 'create'])->name('listing.create');
Route::get('/submit-listing', [ListingController::class, 'create'])->name('listing.submit');
Route::post('/submit-listing', [ListingController::class, 'store'])->name('listing.store');


Route::post('/search', [FrontSearchController::class, 'searchRedirect'])->name('search.redirect');

// Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('login.google');
// Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);


Route::get('/', [HomeController::class, 'index'])->name('homepage');

Route::get('/about', [AboutPageController::class, 'index'])->name('aboutpage');

Route::get('/pricing-plans', function () {
    return view('pages.pricingpage');
});

Route::get('/blog', [BlogPageController::class, 'index'])->name('blog.index');
Route::get('/blog/post/{slug}', [BlogPageController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('blog.show');

Route::get('/terms-and-conditions', function () {
    return view('pages.termspage');
});


Route::get('/citiinfo-australia-business-directory', function () {
    return view('pages.blog-details.blog-1');
});

Route::get('/top-5-towing-companies-in-brisbane', function () {
    return view('pages.blog-details.blog-2');
});


Route::get('/listing', [ListingPageController::class, 'index'])->name('listingpage');
Route::get('/categories', [CategoryPageController::class, 'index'])->name('categorypage');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// SPA auth sync (citiinfo.com.au reads same web session as dashboard login)
Route::get('/auth/session-profile', [AuthController::class, 'profile']);

Route::post('/logout', [LogoutController::class, 'destroy'])
    ->name('logout');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');



// Route::get('/test-mail', function () {
//     Mail::raw('Test Email OK', function ($m) {
//         $m->to('manshu.developer@gmail.com')
//             ->subject('Test Mail');
//     });

//     return 'Mail sent successfully';
// });

Route::post('/get-states', [ListingController::class, 'getStates'])->name('get.states');
Route::post('/get-cities', [ListingController::class, 'getCities'])->name('get.cities');
Route::post('/get-sub-areas', [ListingController::class, 'getSubAreas'])->name('get.sub.areas');


Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard'); // resources/views/user/dashboard.blade.php
    })->name('user.dashboard');

    Route::get('/user/add-listing', [UserAddListingController::class, 'index'])
        ->name('user.addlisting.create');

    Route::post('/user/add-listing', [UserAddListingController::class, 'store'])
        ->name('user.addlisting.store');

    Route::get('/user/messages', [UserMessageController::class, 'index'])->name('user.messages.index');
    Route::get('/user/messages/{enquiry}', [UserMessageController::class, 'show'])->name('user.messages.show');
    Route::post('/user/messages/{enquiry}/reply', [UserMessageController::class, 'reply'])->name('user.messages.reply');
});

Route::middleware(['auth', 'role:user,admin'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'indexAdmin'])->name('wishlist.index');
});



Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin')->group(function () {
    Route::get('/dashboard', [SuperadminDashboardController::class, 'index'])->name('.dashboard');

    Route::get('/listing/pending-count', [SuperadminListingController::class, 'pendingCount'])
        ->name('.listing.pendingCount');

    Route::get('/category', [CategoryController::class, 'index'])->name('.category.index');
    Route::post('/category', [CategoryController::class, 'store'])->name('.category.store');
    Route::put('/category/{id}/update', [CategoryController::class, 'update'])->name('.category.update');
    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('.category.destroy');
    Route::patch('/category/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('.category.toggle-status');
    Route::patch('/category/{id}/toggle-home', [CategoryController::class, 'toggleHome'])
        ->name('.category.toggle-home');


    Route::get('/feature', [FeatureController::class, 'index'])->name('.feature.index');
    Route::post('/feature', [FeatureController::class, 'store'])->name('.feature.store');
    Route::get('/feature/{feature}/edit', [FeatureController::class, 'edit'])->name('.feature.edit');
    Route::put('/feature/{feature}/update', [FeatureController::class, 'update'])->name('.feature.update');
    Route::delete('/feature/{feature}', [FeatureController::class, 'destroy'])->name('.feature.destroy');
    Route::patch('/feature/{feature}/toggle-status', [FeatureController::class, 'toggleStatus'])->name('.feature.toggle-status');


    Route::get('/listing', [SuperadminListingController::class, 'index'])->name('.listing.index');

    Route::patch('/listing/{listing}/approve', [SuperadminListingController::class, 'approve'])
        ->name('.listing.approve');

    Route::get('/listing/{listing}', [SuperadminListingController::class, 'show'])
        ->name('.listing.view');

    Route::get('/listing/{listing}/edit', [SuperadminListingController::class, 'edit'])
        ->name('.listing.edit');

    Route::put('/listing/{listing}', [SuperadminListingController::class, 'update'])->name('.listing.update');

    Route::delete('/listing/{listing}', [SuperadminListingController::class, 'destroy'])
        ->name('.listing.destroy');



    Route::patch('/listing/{id}/restore', [SuperadminListingController::class, 'restore'])
        ->name('.listing.restore');

    Route::patch('/listing/{listing}/toggle-allow', [SuperadminListingController::class, 'toggleAllow'])
        ->name('.listing.toggleAllow');

    Route::patch('/listing/{listing}/toggle-homepage', [SuperadminListingController::class, 'toggleHomepage'])
        ->name('.listing.toggleHomepage');

    Route::get('/user', [SuperadminUserController::class, 'index'])->name('.user.index');

    Route::patch('/user/{user}/toggle-status', [SuperadminUserController::class, 'toggleStatus'])
        ->name('.user.toggleStatus');

    Route::delete('/user/{user}', [SuperadminUserController::class, 'destroy'])
        ->name('.user.destroy');

    Route::get('/seo-users', [SuperadminSeoUserController::class, 'index'])->name('.seo-user.index');
    Route::post('/seo-users', [SuperadminSeoUserController::class, 'store'])->name('.seo-user.store');
    Route::patch('/seo-users/{user}/toggle-status', [SuperadminSeoUserController::class, 'toggleStatus'])
        ->name('.seo-user.toggleStatus');
    Route::delete('/seo-users/{user}', [SuperadminSeoUserController::class, 'destroy'])
        ->name('.seo-user.destroy');


    Route::get('/add-listing', [SuperadminAddListingController::class, 'index'])->name('.addlisting.create');
    Route::post('/add-listing', [SuperadminAddListingController::class, 'store'])->name('.addlisting.store');



    /*
    |--------------------------------------------------------------------------
    | BLOG ROUTES
    |--------------------------------------------------------------------------
    */

    Route::get('/blog', [BlogController::class, 'index'])->name('.blog.index');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('.blog.create');
    Route::post('/blog/store', [BlogController::class, 'store'])->name('.blog.store');
    Route::get('/blog/{blog}/edit', [BlogController::class, 'edit'])->name('.blog.edit');
    Route::put('/blog/{blog}/update', [BlogController::class, 'update'])->name('.blog.update');
    Route::delete('/blog/{blog}', [BlogController::class, 'destroy'])->name('.blog.destroy');

    Route::get('/analytics', [SuperadminAnalyticsController::class, 'index'])->name('.analytics.index');

    Route::get('/messages', [SuperadminInboxController::class, 'index'])->name('.messages.index');
    Route::get('/messages/{enquiry}', [SuperadminInboxController::class, 'show'])->name('.messages.show');
    Route::post('/messages/{enquiry}/reply', [SuperadminInboxController::class, 'reply'])->name('.messages.reply');
    Route::delete('/messages/{enquiry}', [SuperadminInboxController::class, 'destroy'])->name('.messages.destroy');

    Route::get('/reviews', [SuperadminReviewController::class, 'index'])->name('.review.index');
    Route::get('/reviews/{review}/edit', [SuperadminReviewController::class, 'edit'])->name('.review.edit');
    Route::put('/reviews/{review}', [SuperadminReviewController::class, 'update'])->name('.review.update');
    Route::patch('/reviews/{review}/toggle', [SuperadminReviewController::class, 'toggleVisibility'])->name('.review.toggle');
    Route::delete('/reviews/{review}', [SuperadminReviewController::class, 'destroy'])->name('.review.destroy');
});


Route::middleware(['auth', 'role:seo_user,site_user,blog_user'])->prefix('seo-user')->name('blog.')->group(function () {
    Route::get('/dashboard', [BlogUserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/blogs/create', [BlogUserBlogController::class, 'create'])->name('create');
    Route::post('/blogs', [BlogUserBlogController::class, 'store'])->name('store');
    Route::get('/blogs/{blog}/edit', [BlogUserBlogController::class, 'edit'])->name('edit');
    Route::put('/blogs/{blog}', [BlogUserBlogController::class, 'update'])->name('update');
    Route::delete('/blogs/{blog}', [BlogUserBlogController::class, 'destroy'])->name('destroy');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin')->group(function () {



    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('.dashboard');

    //   Announcement Routes
    Route::get('/announcement', [AnnouncementController::class, 'index'])->name('.announcement.index');
    Route::get('/announcement/create', [AnnouncementController::class, 'create'])->name('.announcement.create');
    Route::post('/announcement', [AnnouncementController::class, 'store'])->name('.announcement.store');

    Route::get('/announcement/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('.announcement.edit');
    Route::put('/announcement/{announcement}', [AnnouncementController::class, 'update'])->name('.announcement.update');

    Route::patch('announcement/{announcement}/toggle', [AnnouncementController::class, 'toggle'])->name('.announcement.toggle');
    Route::delete('/announcement/{announcement}', [AnnouncementController::class, 'destroy'])->name('.announcement.destroy');

    // Event Routes
    Route::get('/event', [EventController::class, 'index'])->name('.event.index');
    Route::get('/event/create', [EventController::class, 'create'])->name('.event.create');
    Route::post('/event', [EventController::class, 'store'])->name('.event.store');

    Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('.event.edit');
    Route::put('/event/{event}', [EventController::class, 'update'])->name('.event.update');

    Route::patch('event/{event}/toggle', [EventController::class, 'toggle'])->name('.event.toggle');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('.event.destroy');

    // Coupon Routes    
    Route::get('/coupon', [CouponController::class, 'index'])->name('.coupon.index');
    Route::get('/coupon/create', [CouponController::class, 'create'])->name('.coupon.create');
    Route::post('/coupon', [CouponController::class, 'store'])->name('.coupon.store');

    Route::get('/coupon/{coupon}/edit', [CouponController::class, 'edit'])->name('.coupon.edit');
    Route::put('/coupon/{coupon}', [CouponController::class, 'update'])->name('.coupon.update');

    Route::patch('coupon/{coupon}/toggle', [CouponController::class, 'toggle'])->name('.coupon.toggle');
    Route::delete('/coupon/{coupon}', [CouponController::class, 'destroy'])->name('.coupon.destroy');

    // FAQ Routes
    Route::get('/faq', [FAQController::class, 'index'])->name('.faq.index');
    Route::get('/faq/create', [FAQController::class, 'create'])->name('.faq.create');
    Route::post('/faq', [FAQController::class, 'store'])->name('.faq.store');

    Route::get('/faq/{faq}/edit', [FAQController::class, 'edit'])->name('.faq.edit');
    Route::put('/faq/{faq}', [FAQController::class, 'update'])->name('.faq.update');

    Route::delete('/faq/{faq}', [FAQController::class, 'destroy'])->name('.faq.destroy');

    // Admin Branch Listing

    Route::get('/listing', [AdminListingController::class, 'index'])->name('.listing.index');
    Route::get('/listing/{listing}/edit', [AdminListingController::class, 'edit'])->name('.listing.edit');
    Route::put('/listing/{listing}', [AdminListingController::class, 'update'])->name('.listing.update');
    Route::delete('/listing/{listing}', [AdminListingController::class, 'destroy'])->name('.listing.destroy');
    Route::get('/listings/create', [AdminListingController::class, 'create'])->name('.listings.create');
    Route::post('/listings', [AdminListingController::class, 'store'])->name('.listings.store');
    Route::delete('/listings/gallery/{gallery}', [AdminListingController::class, 'deleteGallery'])
        ->name('.listings.gallery.delete');




    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('.analytics.index');

    Route::get('/inbox', [InboxController::class, 'index'])->name('.inbox.index');
    Route::get('/inbox/{enquiry}', [InboxController::class, 'show'])->name('.inbox.show');
    Route::post('/inbox/{enquiry}/reply', [InboxController::class, 'reply'])->name('.inbox.reply');
    Route::patch('/inbox/{enquiry}/read', [InboxController::class, 'markRead'])->name('.inbox.markRead');
    Route::delete('/inbox/{enquiry}', [InboxController::class, 'destroy'])->name('.inbox.destroy');
});





// Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
//     ->name('admin.dashboard');

Route::get('listing/{slug}', [ListingController::class, 'show'])->name('listingdetail');
Route::post('listing/{slug}/enquiry', [ListingEnquiryController::class, 'store'])->name('listing.enquiry.store');


// Route::get('/gd-check', function () {
//     return extension_loaded('gd') ? 'GD ENABLED ✅' : 'GD NOT ENABLED ❌';
// });

Route::get('/clear-all-cache-now', function () {
    Artisan::call('optimize:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('route:cache');
    Artisan::call('view:clear');
    Artisan::call('view:cache');
    Artisan::call('storage:link');

    return 'All caches cleared! <a href="/">Go Home</a>';
})->name('clear.all');

Route::get('/category/{category}', [FrontSearchController::class, 'listingCategory'])->name('list.category');


// AJAX suggest
Route::get('/ajax/category-suggest', [FrontSearchController::class, 'categorySuggest'])->name('ajax.category.suggest');
Route::get('/ajax/city-suggest', [FrontSearchController::class, 'citySuggest'])->name('ajax.city.suggest');
Route::get('/search', [FrontSearchController::class, 'searchByText'])->name('search.byText');

Route::post('/listing/{slug}/reviews', [ListingController::class, 'storeReview'])
    ->name('listing.reviews.store')
    ->middleware('auth');


// Route::get('/{city}/{category}', [FrontSearchController::class, 'listingByCityCategory'])
//     ->name('city.category');

Route::get('/ajax/city/by-coords', [AjaxLocationController::class, 'cityByCoords'])
    ->name('ajax.city.by-coords');

Route::post('/wishlists/toggle', [WishlistController::class, 'toggle'])
    ->name('wishlist.toggle');

// Debug only: verify session/CSRF on server (APP_DEBUG=true). Remove after fixing 419.
Route::get('/__session-check', function () {
    if (! config('app.debug')) {
        abort(404);
    }

    return response()->json([
        'host'               => request()->getHost(),
        'is_secure'          => request()->isSecure(),
        'forwarded_proto'    => request()->header('X-Forwarded-Proto'),
        'app_url'            => config('app.url'),
        'session_driver'     => config('session.driver'),
        'session_domain'     => config('session.domain'),
        'session_secure'     => config('session.secure'),
        'session_path'       => config('session.path'),
        'csrf_token'         => csrf_token(),
        'session_id'         => session()->getId(),
        'has_session_cookie' => request()->hasCookie(config('session.cookie')),
    ]);
})->middleware('web');
