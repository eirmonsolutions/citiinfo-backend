@extends('layouts.superadmin')

@section('title', 'Analytics')

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>Listing Analytics</h1>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="announcement-inner p-4 text-center">
                <h3 class="mb-1">{{ number_format($totals['listings']) }}</h3>
                <p class="mb-0 text-muted">Total Listings</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="announcement-inner p-4 text-center">
                <h3 class="mb-1">{{ number_format($totals['views']) }}</h3>
                <p class="mb-0 text-muted">Total Profile Views</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="announcement-inner p-4 text-center">
                <h3 class="mb-1">{{ number_format($totals['enquiries']) }}</h3>
                <p class="mb-0 text-muted">Total Enquiries</p>
            </div>
        </div>
    </div>

    <form method="GET" class="d-flex flex-wrap gap-2 mb-3 align-items-center">
        <input type="text" name="q" value="{{ $search }}" class="form-control" style="max-width:280px;" placeholder="Search business or owner...">

        <select name="sort" class="form-select" style="max-width:200px;">
            <option value="views_desc" {{ $sort === 'views_desc' ? 'selected' : '' }}>Most Views</option>
            <option value="views_asc" {{ $sort === 'views_asc' ? 'selected' : '' }}>Least Views</option>
            <option value="enquiries" {{ $sort === 'enquiries' ? 'selected' : '' }}>Most Enquiries</option>
            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
        </select>

        <button type="submit" class="theme-btn">Filter</button>
        <a href="{{ route('superadmin.analytics.index') }}" class="theme-btn">Reset</a>
    </form>

    <section class="table-section table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Business Name</th>
                    <th>Owner</th>
                    <th>Category</th>
                    <th>City</th>
                    <th>Status</th>
                    <th>Profile Views</th>
                    <th>Enquiries</th>
                </tr>
            </thead>
            <tbody>
                @forelse($listings as $index => $listing)
                <tr>
                    <td>{{ $listings->firstItem() + $index }}</td>
                    <td>{{ $listing->business_name }}</td>
                    <td>{{ $listing->user->name ?? '-' }}</td>
                    <td>{{ $listing->categoryRel->name ?? $listing->category ?? '-' }}</td>
                    <td>{{ $listing->cityRel->name ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $listing->status === 'published' ? 'success' : 'secondary' }}">
                            {{ ucfirst($listing->status) }}
                        </span>
                    </td>
                    <td><strong>{{ number_format($listing->views_count) }}</strong></td>
                    <td>{{ number_format($listing->enquiries_count) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">No listings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="mt-3">
        {{ $listings->links() }}
    </div>

</main>
@endsection
