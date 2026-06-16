@extends('layouts.user')

@section('title', 'My Messages')

@push('styles')
<link href="{{ asset('assets/css/messages.css') }}" rel="stylesheet">
@endpush

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>Messages</h1>
    </div>

    <div class="msg-filter-bar">
        <a href="{{ route('user.messages.index', ['tab' => 'sent']) }}"
            class="msg-filter-pill {{ $tab === 'sent' ? 'active' : '' }}">My Messages</a>
        @if($hasListings)
        <a href="{{ route('user.messages.index', ['tab' => 'inbox']) }}"
            class="msg-filter-pill {{ $tab === 'inbox' ? 'active' : '' }}">
            Listing Inbox @if($tab === 'inbox' && $unreadCount > 0)({{ $unreadCount }})@endif
        </a>
        @endif
    </div>

    @if($enquiries->isEmpty())
    <div class="msg-empty-state">
        @if($tab === 'inbox')
        <h2>No messages on your listings</h2>
        <p>When someone sends a message on your listing page, it will appear here.</p>
        @else
        <h2>No messages yet</h2>
        <p>Send a message from any listing page. Replies will show here.</p>
        @endif
    </div>
    @else
    <div class="msg-list-stack">
        @foreach($enquiries as $enquiry)
            @if($tab === 'inbox')
            @include('partials.enquiry-list-card', [
                'enquiry' => $enquiry,
                'showRoute' => route('user.messages.show', $enquiry),
            ])
            @else
            @include('partials.enquiry-sent-card', [
                'enquiry' => $enquiry,
                'showRoute' => route('user.messages.show', $enquiry),
            ])
            @endif
        @endforeach
    </div>

    <div class="mt-3">{{ $enquiries->links() }}</div>
    @endif

</main>
@endsection
