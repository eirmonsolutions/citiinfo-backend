@php
    $showRoute = $showRoute ?? '#';
    $status = 'pending';
    if ($enquiry->hasReply()) {
        $status = 'replied';
    } elseif (!$enquiry->is_read) {
        $status = 'new';
    }
@endphp

<article class="msg-list-card {{ !$enquiry->is_read ? 'unread' : '' }}">
    <div class="msg-list-top">
        <div>
            <h3>{{ $enquiry->name }}</h3>
            <p>
                {{ $enquiry->listing->business_name ?? 'Listing' }}
                @if(!empty($showOwner) && $enquiry->owner)
                    · Owner: {{ $enquiry->owner->name }}
                @endif
                · {{ $enquiry->created_at->format('d M Y, h:i A') }}
            </p>
        </div>
        <span class="msg-status-pill {{ $status }}">
            @if($status === 'replied')
                Replied
            @elseif($status === 'new')
                New
            @else
                Pending
            @endif
        </span>
    </div>

    <div class="msg-list-preview">
        <div class="msg-list-snippet customer">
            <div class="msg-list-snippet-label">
                <span>Customer Message</span>
                <span>{{ $enquiry->email }}</span>
            </div>
            <p class="msg-list-snippet-text">{{ $enquiry->message ?: '—' }}</p>
            @if($enquiry->rating)
            <div class="mt-2" style="color:#f59e0b;font-weight:700;font-size:0.85rem;">
                @for($i = 1; $i <= 5; $i++)
                    {{ $i <= $enquiry->rating ? '★' : '☆' }}
                @endfor
                <span style="color:#64748b;font-weight:600;margin-left:6px;">{{ $enquiry->rating }}/5</span>
            </div>
            @endif
        </div>

        @if($enquiry->hasReply())
        <div class="msg-list-snippet reply">
            <div class="msg-list-snippet-label">
                <span>Your Reply</span>
                <span>{{ $enquiry->replied_at?->format('d M Y, h:i A') }}</span>
            </div>
            <p class="msg-list-snippet-text">{{ $enquiry->admin_reply }}</p>
        </div>
        @endif
    </div>

    <div class="msg-list-footer">
        @if($enquiry->hasReply())
        <div class="msg-list-replied-by">
            Replied by <strong>{{ $enquiry->replier->name ?? 'Admin' }}</strong>
        </div>
        @else
        <div class="msg-list-replied-by text-muted">No reply sent yet</div>
        @endif

        <a href="{{ $showRoute }}" class="theme-btn btn-sm">Open Conversation</a>
    </div>
</article>
