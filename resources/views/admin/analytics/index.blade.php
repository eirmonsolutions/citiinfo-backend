@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>My Listing Analytics</h1>
        <a href="{{ route('admin.inbox.index') }}" class="theme-btn">
            Inbox
            @if($totals['unread_messages'] > 0)
            <span class="badge bg-danger ms-1">{{ $totals['unread_messages'] }}</span>
            @endif
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="announcement-inner p-4 text-center">
                <h3 class="mb-1">{{ number_format($totals['listings']) }}</h3>
                <p class="mb-0 text-muted">My Listings</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="announcement-inner p-4 text-center">
                <h3 class="mb-1">{{ number_format($totals['views']) }}</h3>
                <p class="mb-0 text-muted">Total Profile Views</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="announcement-inner p-4 text-center">
                <h3 class="mb-1">{{ number_format($totals['enquiries']) }}</h3>
                <p class="mb-0 text-muted">Total Enquiries</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="announcement-inner p-4 text-center">
                <h3 class="mb-1">{{ number_format($totals['unread_messages']) }}</h3>
                <p class="mb-0 text-muted">Unread Messages</p>
            </div>
        </div>
    </div>

    @if($listings->isEmpty())
    <section class="announcement-area">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="announcement-inner text-center p-5">
                    <h2>No listings yet</h2>
                    <p class="mb-0">Once your business listing is live, profile views and enquiries will appear here.</p>
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="table-section table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Business Name</th>
                    <th>Category</th>
                    <th>City</th>
                    <th>Status</th>
                    <th>Profile Views</th>
                    <th>Enquiries</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listings as $index => $listing)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $listing->business_name }}</td>
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
                @endforeach
            </tbody>
        </table>
    </section>
    @endif

</main>
@endsection
