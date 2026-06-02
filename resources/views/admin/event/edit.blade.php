@extends('layouts.admin')

@section('title', 'Edit Event')

@section('content')
<main class="main-dashboard">
    <div class="inner">
        <div class="top-heading">
            <h1>Edit Event</h1>
        </div>

        <section class="announcement-form-area">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">

                        <form class="ann-card" method="POST" action="{{ route('admin.event.update', $event->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="ann-card-body">
                                <div class="row g-3">

                                    {{-- Listing --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Choose an event organizer's listing <span class="required">*</span></label>

                                            <div class="custom-select" data-select="" id="eventListingSelect">
                                                <button type="button" class="select-trigger" data-trigger="">
                                                    <span class="select-placeholder" data-label="">Select Listing</span>
                                                    <span class="select-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="m6 9 6 6 6-6"></path>
                                                        </svg>
                                                    </span>
                                                </button>

                                                <input type="hidden" name="listing_id" id="listing_id"
                                                    value="{{ old('listing_id', $event->listing_id) }}">
                                                <input type="hidden" name="listing_name" id="listing_name"
                                                    value="{{ old('listing_name', $event->listing_name) }}">

                                                <div class="select-panel" data-panel="">
                                                    <ul class="select-options" data-options="" id="listingOptions">
                                                        @forelse($listings as $l)
                                                        <li class="select-option" data-id="{{ $l->id }}">{{ $l->business_name }}</li>
                                                        @empty
                                                        <li class="select-option is-disabled" style="pointer-events:none;opacity:.6">No listing found</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>

                                            @error('listing_id') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Event Title --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Title <span class="required">*</span></label>
                                            <input type="text" name="title" class="ann-control" placeholder="Give it a short quick name"
                                                value="{{ old('title', $event->title) }}">
                                            @error('title') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Location --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Event Location <span class="required">*</span></label>
                                            <input type="text" name="location" class="ann-control" placeholder="Address for Google Maps"
                                                value="{{ old('location', $event->location) }}">
                                            @error('location') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Starts --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Starts <span class="required">*</span></label>
                                            <input type="date" name="start_date" class="ann-control"
                                                value="{{ old('start_date', $event->start_date) }}">
                                            @error('start_date') <div class="error-message">{{ $message }}</div> @enderror

                                            <input type="time" name="start_time" class="ann-control mt-2"
                                                value="{{ old('start_time', $event->start_time) }}">
                                            @error('start_time') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Ends --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Ends <span class="required">*</span></label>
                                            <input type="date" name="end_date" class="ann-control"
                                                value="{{ old('end_date', $event->end_date) }}">
                                            @error('end_date') <div class="error-message">{{ $message }}</div> @enderror

                                            <input type="time" name="end_time" class="ann-control mt-2"
                                                value="{{ old('end_time', $event->end_time) }}">
                                            @error('end_time') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Event Description <span class="required">*</span></label>
                                            <textarea name="description" rows="4" class="ann-control textarea-field"
                                                placeholder="Enter description about your event">{{ old('description', $event->description) }}</textarea>
                                            @error('description') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Tickets (custom select like your UI) --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Choose Event Tickets <span class="required">*</span></label>

                                            <div class="custom-select" data-select="" id="ticketPlatformSelect">
                                                <button type="button" class="select-trigger" data-trigger="">
                                                    <span class="select-placeholder" data-label="" id="ticket_placeholder">Select Event Tickets</span>
                                                    <span class="select-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="m6 9 6 6 6-6"></path>
                                                        </svg>
                                                    </span>
                                                </button>

                                                <input type="hidden" name="ticket_platform" id="ticket_platform"
                                                    value="{{ old('ticket_platform', $event->ticket_platform ?? '') }}">

                                                <div class="select-panel" data-panel="">
                                                    <ul class="select-options" data-options="" id="ticketOptions">
                                                        <li class="select-option" data-value="Facebook">Facebook</li>
                                                        <li class="select-option" data-value="Eventbrite">Eventbrite</li>
                                                        <li class="select-option" data-value="Website">Website</li>
                                                        <li class="select-option" data-value="Other">Other</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            @error('ticket_platform') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Ticket URL --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Ticket URL</label>
                                            <input type="url" name="ticket_url" class="ann-control" placeholder="https://"
                                                value="{{ old('ticket_url', $event->ticket_url) }}">
                                            @error('ticket_url') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Featured Image --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Event Featured Image</label>
                                            <input type="file" name="featured_image" class="ann-control">
                                            @error('featured_image') <div class="error-message">{{ $message }}</div> @enderror

                                            @if(!empty($event->featured_image))
                                            <div style="margin-top:8px;">
                                                <small>Current:</small><br>
                                                <img src="{{ asset('storage/'.$event->featured_image) }}" style="max-height:80px;border-radius:6px;">
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="ann-actions">
                                    <a href="{{ route('admin.event.index') }}" class="ann-ghost-btn">Back</a>
                                    <div class="ann-actions-right">
                                        <a href="{{ route('admin.event.index') }}" class="ann-ghost-btn">Cancel</a>
                                        <button type="submit" class="ann-primary-btn">Update</button>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const allSelects = document.querySelectorAll('[data-select]');

        // ---------- LISTING SELECT ----------
        const eventSelect = document.getElementById('eventListingSelect');
        const listingOptions = document.getElementById('listingOptions');
        const listingIdInput = document.getElementById('listing_id');
        const listingNameInput = document.getElementById('listing_name');

        // set initial listing label
        if (eventSelect && listingNameInput && listingNameInput.value) {
            const label = eventSelect.querySelector('[data-label]');
            if (label) label.textContent = listingNameInput.value;
        }

        if (listingOptions) {
            listingOptions.addEventListener('click', (e) => {
                const opt = e.target.closest('.select-option');
                if (!opt || opt.classList.contains('is-disabled')) return;

                const id = opt.dataset.id || '';
                const text = opt.textContent.trim();

                const label = eventSelect.querySelector('[data-label]');
                if (label) label.textContent = text;

                listingIdInput.value = id;
                listingNameInput.value = text;

                eventSelect.classList.remove('is-open');
            });
        }

        // ---------- TICKET PLATFORM SELECT ----------
        const ticketSelect = document.getElementById('ticketPlatformSelect');
        const ticketOptions = document.getElementById('ticketOptions');
        const ticketInput = document.getElementById('ticket_platform');

        // set initial ticket label
        if (ticketSelect && ticketInput && ticketInput.value) {
            const label = ticketSelect.querySelector('[data-label]');
            if (label) label.textContent = ticketInput.value;
        }

        if (ticketOptions) {
            ticketOptions.addEventListener('click', (e) => {
                const opt = e.target.closest('.select-option');
                if (!opt || opt.classList.contains('is-disabled')) return;

                const val = opt.dataset.value || opt.textContent.trim();

                const label = ticketSelect.querySelector('[data-label]');
                if (label) label.textContent = val;

                ticketInput.value = val;

                ticketSelect.classList.remove('is-open');
            });
        }

        // ---------- OPEN / CLOSE ----------
        function closeAll(except = null) {
            allSelects.forEach(s => {
                if (s !== except) s.classList.remove('is-open');
            });
        }

        document.addEventListener('click', () => closeAll());
        allSelects.forEach(sel => sel.addEventListener('click', (e) => e.stopPropagation()));

        document.querySelectorAll('[data-trigger]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const select = btn.closest('[data-select]');
                if (!select || select.classList.contains('is-disabled')) return;

                const isOpen = select.classList.contains('is-open');
                closeAll(select);
                select.classList.toggle('is-open', !isOpen);
            });
        });

    });
</script>
@endsection