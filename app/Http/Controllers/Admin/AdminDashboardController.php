<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessListing;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Coupon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalListings = BusinessListing::where('user_id', auth()->id())->count();
        $totalAnnouncements = Announcement::count();
        $totalEvents = Event::count();
        $totalCoupons = Coupon::count();

        return view('admin.dashboard', compact(
            'totalListings',
            'totalAnnouncements',
            'totalEvents',
            'totalCoupons'
        ));
    }
}