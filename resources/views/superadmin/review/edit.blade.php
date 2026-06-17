@extends('layouts.superadmin')

@section('title', 'Edit Review')

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>Edit Review</h1>
        <a href="{{ route('superadmin.review.index') }}" class="theme-btn">Back to Reviews</a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="announcement-inner p-4" style="align-items:stretch;max-width:720px;margin:0 auto;">
        <p class="mb-3 text-muted">
            Listing: <strong>{{ $review->business->business_name ?? '-' }}</strong>
        </p>

        <form method="POST" action="{{ route('superadmin.review.update', $review) }}" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $review->name) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $review->email) }}" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-control" required>
                    @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ (int) old('rating', $review->rating) === $i ? 'selected' : '' }}>
                        {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                    </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Review Text</label>
                <textarea name="review" class="form-control" rows="6" required minlength="10">{{ old('review', $review->review) }}</textarea>
            </div>

            <div class="col-md-12 d-flex gap-2">
                <button type="submit" class="theme-btn">Save Changes</button>
                <a href="{{ route('superadmin.review.index') }}" class="theme-btn" style="background:#6c757d;">Cancel</a>
            </div>
        </form>
    </div>

</main>
@endsection
