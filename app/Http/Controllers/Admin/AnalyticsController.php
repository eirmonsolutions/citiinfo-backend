<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessListing;
use App\Models\BusinessEnquiry;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        $listings = BusinessListing::query()
            ->where('user_id', $admin->id)
            ->with(['cityRel', 'categoryRel'])
            ->withCount('enquiries')
            ->orderByDesc('views_count')
            ->get();

        $listingIds = $listings->pluck('id');

        $totals = [
            'listings'        => $listings->count(),
            'views'           => (int) $listings->sum('views_count'),
            'enquiries'       => (int) $listings->sum('enquiries_count'),
            'unread_messages' => BusinessEnquiry::whereIn('business_listing_id', $listingIds)
                ->where('is_read', false)
                ->count(),
        ];

        return view('admin.analytics.index', compact('listings', 'totals'));
    }
}
