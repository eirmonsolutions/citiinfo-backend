<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BusinessEnquiry;
use App\Models\BusinessListing;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('q', ''));
        $sort   = $request->get('sort', 'views_desc');

        $query = BusinessListing::query()
            ->with(['cityRel', 'categoryRel', 'user'])
            ->withCount('enquiries');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        match ($sort) {
            'views_asc'  => $query->orderBy('views_count')->orderBy('business_name'),
            'name_asc'   => $query->orderBy('business_name'),
            'enquiries'  => $query->orderByDesc('enquiries_count')->orderByDesc('views_count'),
            default      => $query->orderByDesc('views_count')->orderBy('business_name'),
        };

        $listings = $query->paginate(20)->withQueryString();

        $totals = [
            'listings'  => BusinessListing::count(),
            'views'     => (int) BusinessListing::sum('views_count'),
            'enquiries' => BusinessEnquiry::count(),
        ];

        return view('superadmin.analytics.index', compact('listings', 'totals', 'search', 'sort'));
    }
}
