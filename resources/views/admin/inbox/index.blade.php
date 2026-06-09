@extends('layouts.admin')

@section('title', 'Inbox')

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>Inbox</h1>
        <a href="{{ route('admin.analytics.index') }}" class="theme-btn">Analytics</a>
    </div>

    <div class="d-flex gap-2 flex-wrap mb-3">
        <a href="{{ route('admin.inbox.index', ['filter' => 'all']) }}"
            class="theme-btn {{ $filter === 'all' ? 'active' : '' }}">
            All
        </a>
        <a href="{{ route('admin.inbox.index', ['filter' => 'unread']) }}"
            class="theme-btn {{ $filter === 'unread' ? 'active' : '' }}">
            Unread ({{ $unreadCount }})
        </a>
        <a href="{{ route('admin.inbox.index', ['filter' => 'read']) }}"
            class="theme-btn {{ $filter === 'read' ? 'active' : '' }}">
            Read
        </a>
    </div>

    @if($enquiries->isEmpty())
    <section class="announcement-area">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="announcement-inner text-center p-5">
                    <h2>No messages yet</h2>
                    <p class="mb-0">When someone fills the enquiry form on your listing, their message will appear here.</p>
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
                    <th>From</th>
                    <th>Listing</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enquiries as $index => $enquiry)
                <tr class="{{ !$enquiry->is_read ? 'table-light fw-semibold' : '' }}">
                    <td>{{ $enquiries->firstItem() + $index }}</td>
                    <td>
                        {{ $enquiry->name }}<br>
                        <small class="text-muted">{{ $enquiry->email }}</small>
                    </td>
                    <td>{{ $enquiry->listing->business_name ?? '-' }}</td>
                    <td>{{ $enquiry->phone }}</td>
                    <td style="max-width:220px;">{{ \Illuminate\Support\Str::limit($enquiry->message ?? '-', 60) }}</td>
                    <td>{{ $enquiry->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        @if($enquiry->is_read)
                        <span class="badge bg-secondary">Read</span>
                        @else
                        <span class="badge bg-primary">New</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.inbox.show', $enquiry) }}" class="theme-btn btn-sm">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <div class="mt-3">
        {{ $enquiries->links() }}
    </div>
    @endif

</main>
@endsection
