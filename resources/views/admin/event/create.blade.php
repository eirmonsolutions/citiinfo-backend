@extends('layouts.admin')

@section('title', 'Add Event')

@section('content')

<style>
    .ann-preview-icon {
        width: 150px;
        height: 150px;
    }
</style>
<main class="main-dashboard">
    <div class="inner">
        <div class="top-heading">
            <h1>Add New Event</h1>
        </div>

        <section class="announcement-form-area">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">

                        <!-- Live Preview -->
                        <div class="ann-card ann-preview">
                            <div class="ann-card-head">Live Preview</div>

                            <div class="ann-card-body" style="display:flex;gap:16px;align-items:center;">
                                {{-- Image --}}
                                <div class="ann-preview-icon" id="pvIconWrap" style="display:none; width:60px; height:60px;">
                                    <img id="pvImage" src="" alt="Event image"
                                        style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                                </div>

                                {{-- Texts --}}
                                <div class="ann-preview-texts" style="flex:1;">
                                    <div id="pvTitle" class="ann-preview-title">Event Title</div>
                                    <div id="pvDesc" class="ann-preview-desc">Event description will show here…</div>

                                    {{-- Listing chip --}}
                                    <div id="pvListingChipWrap" style="margin-top:10px; display:none;">
                                        <span id="pvListingChip"
                                            style="display:inline-block;padding:6px 10px;border-radius:999px;font-size:12px;border:1px solid #e5e7eb;">
                                            Listing Name
                                        </span>
                                    </div>

                                    {{-- Location + Date/Time (same line) --}}
                                    <div style="margin-top:8px; font-size:13px; opacity:.9; display:flex; flex-wrap:wrap; gap:10px;">
                                        <div id="pvLocationLine" style="display:none;">
                                            📍 <span id="pvLocationText"></span>
                                        </div>

                                    </div>
                                    {{-- Location + Date/Time (same line) --}}
                                    <div style="margin-top:8px; font-size:13px; opacity:.9; display:flex; flex-wrap:wrap; gap:10px;">

                                        <div id="pvDateTimeLine" style="margin-top:6px;font-size:13px;opacity:.85;display:none;">
                                            🕒 <span id="pvDateText"></span><span id="pvTimeText"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- CTA (Ticket URL link) --}}
                                <a id="pvBtnLink" href="javascript:void(0)"
                                    class="ann-chip"
                                    style="text-decoration:none; cursor:pointer;"
                                    target="_blank" rel="noopener">
                                    View Event
                                </a>
                            </div>

                            {{-- Map Preview --}}
                            <div id="pvMapWrap" style="display:none; margin-top:12px; border-radius:14px; overflow:hidden;">
                                <iframe
                                    id="pvMap"
                                    src=""
                                    width="100%"
                                    height="220"
                                    style="border:0;"
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>



                        <form class="ann-card" method="POST" action="{{ route('admin.event.store') }}" enctype="multipart/form-data">
                            @csrf

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

                                                <input type="hidden" name="listing_id" id="listing_id" value="{{ old('listing_id') }}">
                                                <input type="hidden" name="listing_name" id="listing_name" value="{{ old('listing_name') }}">

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
                                                value="{{ old('title') }}">
                                            @error('title') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Location --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Event Location <span class="required">*</span></label>
                                            <input type="text" name="location" class="ann-control" placeholder="Address for Google Maps"
                                                value="{{ old('location') }}">
                                            @error('location') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Starts --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Starts <span class="required">*</span></label>
                                            <input type="date" name="start_date" class="ann-control" value="{{ old('start_date') }}">
                                            @error('start_date') <div class="error-message">{{ $message }}</div> @enderror

                                            <input type="time" name="start_time" class="ann-control mt-2" value="{{ old('start_time') }}">
                                            @error('start_time') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Ends --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Ends <span class="required">*</span></label>
                                            <input type="date" name="end_date" class="ann-control" value="{{ old('end_date') }}">
                                            @error('end_date') <div class="error-message">{{ $message }}</div> @enderror

                                            <input type="time" name="end_time" class="ann-control mt-2" value="{{ old('end_time') }}">
                                            @error('end_time') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Event Description <span class="required">*</span></label>
                                            <textarea name="description" rows="4" class="ann-control textarea-field"
                                                placeholder="Enter description about your event">{{ old('description') }}</textarea>
                                            @error('description') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Tickets --}}

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Choose Event Tickets <span class="required">*</span></label>

                                            <div class="custom-select" data-select id="ticketPlatformSelect">
                                                <button type="button" class="select-trigger" data-trigger>
                                                    <span class="select-placeholder" data-label>
                                                        Select Event Tickets
                                                    </span>
                                                    <span class="select-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="m6 9 6 6 6-6"></path>
                                                        </svg>
                                                    </span>
                                                </button>

                                                {{-- actual value yahan store hogi --}}
                                                <input type="hidden" name="ticket_platform" id="ticket_platform"
                                                    value="{{ old('ticket_platform', $event->ticket_platform ?? '') }}">

                                                <div class="select-panel" data-panel>
                                                    <ul class="select-options" data-options id="ticketOptions">
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
                                                value="{{ old('ticket_url') }}">
                                            @error('ticket_url') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Featured Image --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Event Featured Image</label>
                                            <input type="file" name="featured_image" class="ann-control">
                                            @error('featured_image') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                </div>

                                <div class="ann-actions">
                                    <a href="{{ route('admin.event.index') }}" class="ann-ghost-btn">Back</a>
                                    <div class="ann-actions-right">
                                        <a href="{{ route('admin.event.index') }}" class="ann-ghost-btn">Cancel</a>
                                        <button type="submit" class="ann-primary-btn">Save</button>
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

        function closeAll(except = null) {
            allSelects.forEach(s => {
                if (s !== except) s.classList.remove('is-open');
            });
        }

        document.addEventListener('click', () => closeAll());
        allSelects.forEach(sel => sel.addEventListener('click', (e) => e.stopPropagation()));

        // open/close trigger (same for all custom selects)
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

        // ✅ helper: bind options click to update label + hidden input
        function bindCustomSelect({
            selectEl,
            optionsEl,
            hiddenInput,
            valueAttr,
            oldValue
        }) {
            if (!selectEl || !optionsEl || !hiddenInput) return;

            // old value show on load
            if (oldValue) {
                const label = selectEl.querySelector('[data-label]');
                if (label) label.textContent = oldValue;
            }

            optionsEl.addEventListener('click', (e) => {
                const opt = e.target.closest('.select-option');
                if (!opt || opt.classList.contains('is-disabled')) return;

                const val = opt.dataset[valueAttr] || '';
                const text = opt.textContent.trim();

                // label update
                const label = selectEl.querySelector('[data-label]');
                if (label) label.textContent = text;

                // hidden input value set
                hiddenInput.value = val;

                // close dropdown
                selectEl.classList.remove('is-open');
            });
        }

        // =========================
        // ✅ 1) Listing select bind
        // =========================
        const eventSelect = document.getElementById('eventListingSelect');
        const listingOptions = document.getElementById('listingOptions');
        const listingIdInput = document.getElementById('listing_id');
        const listingNameInput = document.getElementById('listing_name');

        // old listing name label set (same as your logic)
        const oldListingName = listingNameInput ? listingNameInput.value : '';

        if (eventSelect && listingOptions) {
            listingOptions.addEventListener('click', (e) => {
                const opt = e.target.closest('.select-option');
                if (!opt || opt.classList.contains('is-disabled')) return;

                const id = opt.dataset.id || '';
                const text = opt.textContent.trim();

                const label = eventSelect.querySelector('[data-label]');
                if (label) label.textContent = text;

                if (listingIdInput) listingIdInput.value = id;
                if (listingNameInput) listingNameInput.value = text;

                eventSelect.classList.remove('is-open');
            });
        }

        if (oldListingName && eventSelect) {
            const label = eventSelect.querySelector('[data-label]');
            if (label) label.textContent = oldListingName;
        }

        // =========================
        // ✅ 2) Ticket select bind (NEW)
        // =========================
        const ticketSelect = document.getElementById('ticketPlatformSelect');
        const ticketOptions = document.getElementById('ticketOptions');
        const ticketHidden = document.getElementById('ticket_platform');

        const oldTicketVal = ticketHidden ? ticketHidden.value : '';

        bindCustomSelect({
            selectEl: ticketSelect,
            optionsEl: ticketOptions,
            hiddenInput: ticketHidden,
            valueAttr: 'value', // because options have data-value="Facebook"
            oldValue: oldTicketVal // show old selected text on reload
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        

        // =========================================
        // ✅ 1) LISTING custom select (your same logic)
        // =========================================
        const eventSelect = document.getElementById('eventListingSelect');
        const listingOptions = document.getElementById('listingOptions');
        const listingIdInput = document.getElementById('listing_id');
        const listingNameInput = document.getElementById('listing_name');

        // old label set
        const oldListingName = listingNameInput ? listingNameInput.value : '';
        if (oldListingName && eventSelect) {
            const label = eventSelect.querySelector('[data-label]');
            if (label) label.textContent = oldListingName;
        }

        if (eventSelect && listingOptions) {
            listingOptions.addEventListener('click', (e) => {
                const opt = e.target.closest('.select-option');
                if (!opt || opt.classList.contains('is-disabled')) return;

                const id = opt.dataset.id || '';
                const text = opt.textContent.trim();

                const label = eventSelect.querySelector('[data-label]');
                if (label) label.textContent = text;

                if (listingIdInput) listingIdInput.value = id;
                if (listingNameInput) listingNameInput.value = text;

                eventSelect.classList.remove('is-open');

                // live preview update
                updatePreview();
            });
        }

        // =========================================
        // ✅ 2) TICKET PLATFORM custom select (NEW)
        // =========================================
        const ticketSelect = document.getElementById('ticketPlatformSelect');
        const ticketOptions = document.getElementById('ticketOptions');
        const ticketHiddenInp = document.getElementById('ticket_platform'); // hidden input name="ticket_platform"

        // show old selected in label
        function syncTicketLabelFromHidden() {
            if (!ticketSelect || !ticketHiddenInp) return;
            const val = (ticketHiddenInp.value || '').trim();
            const label = ticketSelect.querySelector('[data-label]');
            if (!label) return;

            if (!val) {
                label.textContent = 'Select Event Tickets';
                return;
            }

            // find option text by data-value
            const opt = ticketOptions ? ticketOptions.querySelector(`.select-option[data-value="${CSS.escape(val)}"]`) : null;
            label.textContent = opt ? opt.textContent.trim() : val;
        }

        syncTicketLabelFromHidden();

        if (ticketSelect && ticketOptions && ticketHiddenInp) {
            ticketOptions.addEventListener('click', (e) => {
                const opt = e.target.closest('.select-option');
                if (!opt || opt.classList.contains('is-disabled')) return;

                const val = opt.dataset.value || '';
                const text = opt.textContent.trim();

                const label = ticketSelect.querySelector('[data-label]');
                if (label) label.textContent = text;

                ticketHiddenInp.value = val;

                ticketSelect.classList.remove('is-open');

                // live preview update
                updatePreview();
            });
        }

        // =========================================
        // ✅ 3) LIVE PREVIEW (Title/Desc/Location/Map/Date-Time/Listing/CTA Link)
        // =========================================
        const pvTitle = document.getElementById('pvTitle');
        const pvDesc = document.getElementById('pvDesc');

        const pvLocationLine = document.getElementById('pvLocationLine');
        const pvLocationText = document.getElementById('pvLocationText');

        const pvMapWrap = document.getElementById('pvMapWrap');
        const pvMap = document.getElementById('pvMap');

        const pvIconWrap = document.getElementById('pvIconWrap');
        const pvImage = document.getElementById('pvImage');

        // optional chips/badges (agar aapne HTML me add kiye hain)
        const pvListingChip = document.getElementById('pvListingChip'); // optional
        const pvDateBadge = document.getElementById('pvDateBadge'); // optional

        // CTA (aapka "View Event" button)
        const pvBtn = document.getElementById('pvBtn'); // span OR a tag

        // form inputs
        const inpTitle = document.querySelector('input[name="title"]');
        const inpDesc = document.querySelector('textarea[name="description"]');
        const inpLocation = document.querySelector('input[name="location"]');

        const inpStartDate = document.querySelector('input[name="start_date"]');
        const inpStartTime = document.querySelector('input[name="start_time"]');
        const inpEndDate = document.querySelector('input[name="end_date"]');
        const inpEndTime = document.querySelector('input[name="end_time"]');

        const inpTicketUrl = document.querySelector('input[name="ticket_url"]');

        const inpFeatured = document.querySelector('input[name="featured_image"]');

        // --- helpers ---
        function fmtDDMMYYYY(yyyy_mm_dd) {
            if (!yyyy_mm_dd) return '';
            const [y, m, d] = yyyy_mm_dd.split('-');
            if (!y || !m || !d) return '';
            return `${d}-${m}-${y}`;
        }

        function fmtTime12(timeHHMM) {
            if (!timeHHMM) return '';
            const [hh, mm] = timeHHMM.split(':');
            if (hh == null || mm == null) return '';
            let h = parseInt(hh, 10);
            if (Number.isNaN(h)) return '';
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12;
            if (h === 0) h = 12;
            return `${h}:${mm} ${ampm}`;
        }

        // your request: "yahi pe date uske samne time ussi ke samne to date fir time"
        function buildDateTimeLine() {
            const sd = fmtDDMMYYYY(inpStartDate?.value || '');
            const st = fmtTime12(inpStartTime?.value || '');
            const ed = fmtDDMMYYYY(inpEndDate?.value || '');
            const et = fmtTime12(inpEndTime?.value || '');

            // Start: date time  |  End: date time
            const startPart = (sd || st) ? `${sd}${sd && st ? ' ' : ''}${st}` : '';
            const endPart = (ed || et) ? `${ed}${ed && et ? ' ' : ''}${et}` : '';

            if (startPart && endPart) return `${startPart}  •  ${endPart}`;
            return startPart || endPart || '';
        }

        function updateMap(locationValue) {
            const loc = (locationValue || '').trim();
            if (!loc) {
                if (pvLocationLine) pvLocationLine.style.display = 'none';
                if (pvLocationText) pvLocationText.textContent = '';
                if (pvMapWrap) pvMapWrap.style.display = 'none';
                if (pvMap) pvMap.src = '';
                return;
            }

            if (pvLocationLine) pvLocationLine.style.display = 'block';
            if (pvLocationText) pvLocationText.textContent = loc;

            if (pvMapWrap && pvMap) {
                const q = encodeURIComponent(loc);
                pvMap.src = `https://www.google.com/maps?q=${q}&output=embed`;
                pvMapWrap.style.display = 'block';
            }
        }

        function updateCtaLink() {
            const url = (inpTicketUrl?.value || '').trim();

            if (!pvBtn) return;

            // if pvBtn is <a>
            if (pvBtn.tagName.toLowerCase() === 'a') {
                pvBtn.setAttribute('href', url || 'javascript:void(0)');
                pvBtn.setAttribute('target', '_blank');
                pvBtn.style.pointerEvents = url ? 'auto' : 'none';
                pvBtn.style.opacity = url ? '1' : '0.6';
                return;
            }

            // if pvBtn is <span> (your current)
            pvBtn.style.cursor = url ? 'pointer' : 'not-allowed';
            pvBtn.style.opacity = url ? '1' : '0.6';
            pvBtn.onclick = () => {
                if (!url) return;
                window.open(url, '_blank', 'noopener,noreferrer');
            };
        }

        // (OPTIONAL) If you want date-time line inside same text area,
        // add a placeholder span in blade:
        // <div id="pvDateTimeLine" ...></div>
        const pvDateTimeLine = document.getElementById('pvDateTimeLine'); // optional

        function updatePreview() {
            // title
            const t = (inpTitle?.value || '').trim();
            if (pvTitle) pvTitle.textContent = t || 'Event Title';

            // desc
            const d = (inpDesc?.value || '').trim();
            if (pvDesc) pvDesc.textContent = d || 'Event description will show here…';

            // listing chip (optional)
            const listingName = (listingNameInput?.value || '').trim();
            if (pvListingChip) {
                if (listingName) {
                    pvListingChip.style.display = 'inline-flex';
                    pvListingChip.textContent = listingName;
                } else {
                    pvListingChip.style.display = 'none';
                }
            }

            // date badge (optional)
            const dtLine = buildDateTimeLine();
            if (pvDateBadge) {
                if (dtLine) {
                    pvDateBadge.style.display = 'inline-flex';
                    pvDateBadge.textContent = dtLine;
                } else {
                    pvDateBadge.style.display = 'none';
                }
            }

            // if you are using pvDateTimeLine inside texts
            if (pvDateTimeLine) {
                if (dtLine) {
                    pvDateTimeLine.style.display = 'block';
                    pvDateTimeLine.textContent = dtLine;
                } else {
                    pvDateTimeLine.style.display = 'none';
                    pvDateTimeLine.textContent = '';
                }
            }

            // location + map
            updateMap(inpLocation?.value || '');

            // CTA link from Ticket URL
            updateCtaLink();
        }

        // featured image preview
        if (inpFeatured) {
            inpFeatured.addEventListener('change', function() {
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) {
                    if (pvIconWrap) pvIconWrap.style.display = 'none';
                    if (pvImage) pvImage.src = '';
                    return;
                }
                const url = URL.createObjectURL(file);
                if (pvImage) pvImage.src = url;
                if (pvIconWrap) pvIconWrap.style.display = 'block';
            });
        }

        // input listeners
        [
            inpTitle, inpDesc, inpLocation,
            inpStartDate, inpStartTime, inpEndDate, inpEndTime,
            inpTicketUrl
        ].forEach(el => {
            if (!el) return;
            el.addEventListener('input', updatePreview);
            el.addEventListener('change', updatePreview);
        });

        // initial sync
        updatePreview();

    });
</script>


@endsection