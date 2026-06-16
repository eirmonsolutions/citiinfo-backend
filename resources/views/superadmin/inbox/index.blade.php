@extends('layouts.superadmin')

@section('title', 'Messages')

@push('styles')
<link href="{{ asset('assets/css/messages.css') }}" rel="stylesheet">
@endpush

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>All Messages</h1>
    </div>

    <div class="msg-filter-bar">
        <a href="{{ route('superadmin.messages.index', ['filter' => 'all']) }}" class="msg-filter-pill {{ $filter === 'all' ? 'active' : '' }}">All</a>
        <a href="{{ route('superadmin.messages.index', ['filter' => 'unread']) }}" class="msg-filter-pill {{ $filter === 'unread' ? 'active' : '' }}">Unread ({{ $unreadCount }})</a>
        <a href="{{ route('superadmin.messages.index', ['filter' => 'pending']) }}" class="msg-filter-pill {{ $filter === 'pending' ? 'active' : '' }}">Awaiting Reply</a>
        <a href="{{ route('superadmin.messages.index', ['filter' => 'replied']) }}" class="msg-filter-pill {{ $filter === 'replied' ? 'active' : '' }}">Replied</a>
    </div>

    @if($enquiries->isEmpty())
    <div class="msg-empty-state">
        <h2>No messages yet</h2>
        <p>When visitors send messages from listing pages, they will appear here.</p>
    </div>
    @else
    <div class="msg-list-stack">
        @foreach($enquiries as $enquiry)
        @include('partials.enquiry-list-card', [
            'enquiry' => $enquiry,
            'showRoute' => route('superadmin.messages.show', $enquiry),
            'showOwner' => true,
        ])
        @endforeach
    </div>

    <div class="mt-3">{{ $enquiries->links() }}</div>
    @endif

</main>
@endsection
