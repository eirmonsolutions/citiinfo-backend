@php
    $status = 'pending';
    if ($enquiry->hasReply()) {
        $status = 'replied';
    } elseif (!$enquiry->is_read) {
        $status = 'new';
    }
@endphp

<div class="msg-page-card">
    <div class="msg-page-header">
        <div>
            <h2>{{ $enquiry->listing->business_name ?? 'Business Listing' }}</h2>
            <p>Customer enquiry from listing page</p>
        </div>
        <span class="msg-status-pill {{ $status }}">
            @if($status === 'replied')
                Replied
            @elseif($status === 'new')
                New Message
            @else
                Awaiting Reply
            @endif
        </span>
    </div>

    <div class="msg-meta-grid">
        <div class="msg-meta-item">
            <span class="msg-meta-label">From</span>
            <span class="msg-meta-value">{{ $enquiry->name }}</span>
        </div>
        <div class="msg-meta-item">
            <span class="msg-meta-label">Email</span>
            <span class="msg-meta-value"><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></span>
        </div>
        <div class="msg-meta-item">
            <span class="msg-meta-label">Phone</span>
            <span class="msg-meta-value"><a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a></span>
        </div>
        <div class="msg-meta-item">
            <span class="msg-meta-label">Received</span>
            <span class="msg-meta-value">{{ $enquiry->created_at->format('d M Y, h:i A') }}</span>
        </div>
        @if($enquiry->hasReply())
        <div class="msg-meta-item">
            <span class="msg-meta-label">Replied By</span>
            <span class="msg-meta-value">{{ $enquiry->replier->name ?? 'Admin' }}</span>
        </div>
        <div class="msg-meta-item">
            <span class="msg-meta-label">Replied At</span>
            <span class="msg-meta-value">{{ $enquiry->replied_at?->format('d M Y, h:i A') }}</span>
        </div>
        @endif
    </div>

    <div class="msg-thread">
        <div class="msg-bubble incoming">
            <div class="msg-bubble-label">
                <span>{{ $enquiry->name }}</span>
                <span class="msg-bubble-time">{{ $enquiry->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <p class="msg-bubble-body">{{ $enquiry->message ?: '—' }}</p>
            @if($enquiry->rating)
            <div class="mt-2" style="color:#f59e0b;font-weight:700;">
                @for($i = 1; $i <= 5; $i++){{ $i <= $enquiry->rating ? '★' : '☆' }}@endfor
                <span style="color:#64748b;font-weight:600;margin-left:6px;">{{ $enquiry->rating }}/5 Review</span>
            </div>
            @endif
        </div>

        @if($enquiry->hasReply())
        <div class="msg-bubble outgoing">
            <div class="msg-bubble-label">
                <span>{{ $enquiry->replier->name ?? 'You' }}</span>
                <span class="msg-bubble-time">{{ $enquiry->replied_at?->format('d M Y, h:i A') }}</span>
            </div>
            <p class="msg-bubble-body">{{ $enquiry->admin_reply }}</p>
        </div>
        @endif
    </div>

    @if(!empty($showReplyForm))
    <div class="msg-compose">
        <h3>{{ $enquiry->hasReply() ? 'Update your reply' : 'Write a reply' }}</h3>
        <form method="POST" action="{{ $replyRoute }}">
            @csrf
            <textarea name="admin_reply" required placeholder="Type your reply to the customer...">{{ old('admin_reply', $enquiry->admin_reply) }}</textarea>
            @error('admin_reply') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
            <div class="msg-compose-actions">
                <button type="submit" class="msg-btn-primary">Send Reply</button>
            </div>
        </form>
    </div>
    @endif
</div>
