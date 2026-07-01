@php
    $openStatus = listingOpenStatus($listing);
@endphp

@if(($listing->hours ?? collect())->isNotEmpty())
<div class="status-badge {{ $openStatus['class'] }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock">
        <path d="M12 6v6l4 2" />
        <circle cx="12" cy="12" r="10" />
    </svg>
    {{ $openStatus['text'] }}
</div>
@endif
