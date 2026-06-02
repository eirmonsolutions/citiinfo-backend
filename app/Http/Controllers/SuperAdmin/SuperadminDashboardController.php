<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BusinessListing;
use App\Models\Category;
use App\Models\Feature;
use App\Models\User;

class SuperadminDashboardController extends Controller
{
    public function index()
    {
        $totalListings = BusinessListing::count();
        $totalCategories = Category::count();
        $totalFeatures = Feature::count();
        $totalUsers = User::count();

        return view('superadmin.dashboard', compact(
            'totalListings',
            'totalCategories',
            'totalFeatures',
            'totalUsers'
        ));
    }
}
