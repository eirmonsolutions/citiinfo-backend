@extends('layouts.admin')

@section('title', 'Message')

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>Enquiry Message</h1>
        <a href="{{ route('admin.inbox.index') }}" class="theme-btn">Back to Inbox</a>
    </div>

    <div class="announcement-inner p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Listing</strong>
                <p class="mb-0">{{ $enquiry->listing->business_name ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <strong>Received</strong>
                <p class="mb-0">{{ $enquiry->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div class="col-md-4">
                <strong>Name</strong>
                <p class="mb-0">{{ $enquiry->name }}</p>
            </div>
            <div class="col-md-4">
                <strong>Email</strong>
                <p class="mb-0"><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></p>
            </div>
            <div class="col-md-4">
                <strong>Phone</strong>
                <p class="mb-0"><a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a></p>
            </div>
            <div class="col-12">
                <strong>Message</strong>
                <p class="mb-0" style="white-space:pre-wrap;">{{ $enquiry->message ?: '—' }}</p>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            @if(!$enquiry->is_read)
            <form method="POST" action="{{ route('admin.inbox.markRead', $enquiry) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="theme-btn">Mark as Read</button>
            </form>
            @endif

            <form method="POST" action="{{ route('admin.inbox.destroy', $enquiry) }}"
                onsubmit="return confirm('Delete this message?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="theme-btn" style="background:#dc3545;border-color:#dc3545;">Delete</button>
            </form>
        </div>
    </div>

</main>
@endsection
