@extends('layouts.user')

@section('title', 'Message')

@push('styles')
<link href="{{ asset('assets/css/messages.css') }}" rel="stylesheet">
@endpush

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>{{ $mode === 'inbox' ? 'Customer Message' : 'My Message' }}</h1>
        <a href="{{ route('user.messages.index', ['tab' => $mode]) }}" class="theme-btn">Back</a>
    </div>

    @if(session('success'))
    <div class="msg-alert-success">{{ session('success') }}</div>
    @endif

    @if($mode === 'sent')
    <div class="msg-page-card">
        <div class="msg-page-header">
            <div>
                <h2>{{ $enquiry->listing->business_name ?? 'Business Listing' }}</h2>
                <p>Your message sent on {{ $enquiry->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <span class="msg-status-pill {{ $enquiry->hasReply() ? 'replied' : 'pending' }}">
                {{ $enquiry->hasReply() ? 'Reply Received' : 'Waiting' }}
            </span>
        </div>

        <div class="msg-thread">
            <div class="msg-bubble outgoing" style="align-self:flex-start;background:linear-gradient(135deg,#0f172a 0%,#334155 100%);">
                <div class="msg-bubble-label">
                    <span>You</span>
                    <span class="msg-bubble-time">{{ $enquiry->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <p class="msg-bubble-body">{{ $enquiry->message ?: '—' }}</p>
            </div>

            @if($enquiry->hasReply())
            <div class="msg-bubble incoming">
                <div class="msg-bubble-label">
                    <span>{{ $enquiry->replier->name ?? 'Business' }}</span>
                    <span class="msg-bubble-time">{{ $enquiry->replied_at?->format('d M Y, h:i A') }}</span>
                </div>
                <p class="msg-bubble-body">{{ $enquiry->admin_reply }}</p>
            </div>
            @else
            <div class="msg-waiting-box">Waiting for a reply from the business owner.</div>
            @endif
        </div>
    </div>
    @else
    @include('partials.enquiry-detail', [
        'enquiry' => $enquiry,
        'showReplyForm' => true,
        'replyRoute' => route('user.messages.reply', $enquiry),
    ])
    @endif

</main>
@endsection
