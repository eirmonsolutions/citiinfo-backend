@extends('layouts.superadmin')

@section('title', 'Message')

@push('styles')
<link href="{{ asset('assets/css/messages.css') }}" rel="stylesheet">
@endpush

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>Customer Message</h1>
        <a href="{{ route('superadmin.messages.index') }}" class="theme-btn">Back to Messages</a>
    </div>

    @if(session('success'))
    <div class="msg-alert-success">{{ session('success') }}</div>
    @endif

    @include('partials.enquiry-detail', [
        'enquiry' => $enquiry,
        'showReplyForm' => true,
        'replyRoute' => route('superadmin.messages.reply', $enquiry),
    ])

    <div class="msg-compose-actions" style="max-width:960px;margin:16px auto 0;">
        <form method="POST" action="{{ route('superadmin.messages.destroy', $enquiry) }}"
            onsubmit="return confirm('Delete this message?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="msg-btn-danger">Delete Message</button>
        </form>
    </div>

</main>
@endsection
