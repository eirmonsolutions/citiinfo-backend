@extends('layouts.admin')

@section('title', 'Messages')

@push('styles')
<link href="{{ asset('assets/css/messages.css') }}" rel="stylesheet">
@endpush

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>Messages</h1>
        <a href="{{ route('admin.analytics.index') }}" class="theme-btn">Analytics</a>
    </div>

    <div class="msg-filter-bar">
        <a href="{{ route('admin.inbox.index', ['tab' => 'inbox']) }}"
            class="msg-filter-pill {{ ($tab ?? 'inbox') === 'inbox' ? 'active' : '' }}">
            Listing Inbox
            @if(($tab ?? 'inbox') === 'inbox' && $unreadCount > 0)
            ({{ $unreadCount }})
            @endif
        </a>
        <a href="{{ route('admin.inbox.index', ['tab' => 'sent']) }}"
            class="msg-filter-pill {{ ($tab ?? 'inbox') === 'sent' ? 'active' : '' }}">
            My Sent Messages
        </a>
    </div>

    @if(($tab ?? 'inbox') === 'inbox')
    <div class="msg-filter-bar">
        <a href="{{ route('admin.inbox.index', ['tab' => 'inbox', 'filter' => 'all']) }}"
            class="msg-filter-pill {{ $filter === 'all' ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.inbox.index', ['tab' => 'inbox', 'filter' => 'unread']) }}"
            class="msg-filter-pill {{ $filter === 'unread' ? 'active' : '' }}">Unread ({{ $unreadCount }})</a>
        <a href="{{ route('admin.inbox.index', ['tab' => 'inbox', 'filter' => 'pending']) }}"
            class="msg-filter-pill {{ $filter === 'pending' ? 'active' : '' }}">Awaiting Reply</a>
        <a href="{{ route('admin.inbox.index', ['tab' => 'inbox', 'filter' => 'replied']) }}"
            class="msg-filter-pill {{ $filter === 'replied' ? 'active' : '' }}">Replied</a>
    </div>
    @endif

    @if($enquiries->isEmpty())
    <div class="msg-empty-state">
        @if(($tab ?? 'inbox') === 'sent')
        <h2>No sent messages yet</h2>
        <p>When you send a message from any listing page (while logged in), it will appear here with the business reply.</p>
        @else
        <h2>No messages on your listings</h2>
        <p>When a customer sends a message on your listing page, it will appear here.</p>
        @endif
    </div>
    @else
    <div class="msg-list-stack">
        @foreach($enquiries as $enquiry)
            @if(($tab ?? 'inbox') === 'sent')
            @include('partials.enquiry-sent-card', [
                'enquiry' => $enquiry,
                'showRoute' => route('admin.inbox.show', $enquiry),
            ])
            @else
            @include('partials.enquiry-list-card', [
                'enquiry' => $enquiry,
                'showRoute' => route('admin.inbox.show', $enquiry),
            ])
            @endif
        @endforeach
    </div>

    <div class="mt-3">
        {{ $enquiries->appends(['tab' => $tab ?? 'inbox', 'filter' => $filter ?? 'all'])->links() }}
    </div>
    @endif

</main>
@endsection
