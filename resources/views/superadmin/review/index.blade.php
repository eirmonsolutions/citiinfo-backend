@extends('layouts.superadmin')

@section('title', 'Reviews')

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>Customer Reviews</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex gap-2 flex-wrap mb-3">
        <a href="{{ route('superadmin.review.index', ['filter' => 'all']) }}"
            class="theme-btn {{ $filter === 'all' ? 'active' : '' }}">
            All ({{ $counts['all'] }})
        </a>
        <a href="{{ route('superadmin.review.index', ['filter' => 'visible']) }}"
            class="theme-btn {{ $filter === 'visible' ? 'active' : '' }}">
            Visible ({{ $counts['visible'] }})
        </a>
        <a href="{{ route('superadmin.review.index', ['filter' => 'hidden']) }}"
            class="theme-btn {{ $filter === 'hidden' ? 'active' : '' }}">
            Hidden ({{ $counts['hidden'] }})
        </a>
    </div>

    <section class="table-section table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Listing</th>
                    <th>From</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $index => $review)
                <tr>
                    <td>{{ $reviews->firstItem() + $index }}</td>
                    <td>{{ $review->business->business_name ?? '-' }}</td>
                    <td>
                        <strong>{{ $review->name }}</strong><br>
                        <small class="text-muted">{{ $review->email }}</small>
                    </td>
                    <td>
                        <span style="color:#f59e0b;font-weight:700;">
                            {{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}
                        </span>
                        <small class="d-block text-muted">{{ $review->rating }}/5</small>
                    </td>
                    <td style="max-width:260px;">{{ \Illuminate\Support\Str::limit($review->review, 80) }}</td>
                    <td>{{ $review->created_at->format('d M Y') }}</td>
                    <td>
                        @if($review->is_approved)
                        <span class="badge bg-success">Visible</span>
                        @else
                        <span class="badge bg-secondary">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('superadmin.review.edit', $review) }}" class="theme-btn btn-sm">Edit</a>

                            <form method="POST" action="{{ route('superadmin.review.toggle', $review) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="theme-btn btn-sm">
                                    {{ $review->is_approved ? 'Hide' : 'Show' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('superadmin.review.destroy', $review) }}"
                                onsubmit="return confirm('Delete this review permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="theme-btn btn-sm" style="background:#dc3545;border-color:#dc3545;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">No reviews found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="mt-3">{{ $reviews->links() }}</div>

</main>
@endsection
