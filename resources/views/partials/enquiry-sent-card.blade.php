@php
    $showRoute = $showRoute ?? '#';
    $status = $enquiry->hasReply() ? 'replied' : 'pending';
@endphp

<article class="msg-list-card">
    <div class="msg-list-top">
        <div>
            <h3>{{ $enquiry->listing->business_name ?? 'Business Listing' }}</h3>
            <p>Sent {{ $enquiry->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <span class="msg-status-pill {{ $status }}">
            {{ $enquiry->hasReply() ? 'Reply Received' : 'Waiting' }}
        </span>
    </div>

    <div class="msg-list-preview">
        <div class="msg-list-snippet customer">
            <div class="msg-list-snippet-label">
                <span>Your Message</span>
            </div>
            <p class="msg-list-snippet-text">{{ $enquiry->message ?: '—' }}</p>
            @if($enquiry->rating)
            <div class="mt-2" style="color:#f59e0b;font-weight:700;font-size:0.85rem;">
                @for($i = 1; $i <= 5; $i++)
                    {{ $i <= $enquiry->rating ? '★' : '☆' }}
                @endfor
            </div>
            @endif
        </div>

        @if($enquiry->hasReply())
        <div class="msg-list-snippet reply">
            <div class="msg-list-snippet-label">
                <span>Reply From Business</span>
                <span>{{ $enquiry->replied_at?->format('d M Y, h:i A') }}</span>
            </div>
            <p class="msg-list-snippet-text">{{ $enquiry->admin_reply }}</p>
        </div>
        @endif
    </div>

    <div class="msg-list-footer">
        @if($enquiry->hasReply())
        <div class="msg-list-replied-by">
            Replied by <strong>{{ $enquiry->replier->name ?? 'Business Owner' }}</strong>
        </div>
        @else
        <div class="msg-list-replied-by text-muted">Waiting for business reply...</div>
        @endif

        <a href="{{ $showRoute }}" class="theme-btn btn-sm">View Details</a>
    </div>
</article>
