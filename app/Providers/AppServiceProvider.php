<?php

namespace App\Providers;

use App\Models\Wishlist;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            if (in_array($view->getName(), ['auth.login', 'auth.register'], true)) {
                $view->with('wishlistCount', 0);

                return;
            }
            $userId = auth()->id();

            $wishlistCount = $userId
                ? \Cache::remember("wishlist_count_{$userId}", 600, fn() => Wishlist::where('user_id', $userId)->count())
                : 0;

            $view->with('wishlistCount', $wishlistCount);
        });
    }
}
