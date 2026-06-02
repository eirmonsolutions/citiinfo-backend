@extends('layouts.admin')

@section('title', 'Add FAQ')

@section('content')

<main class="main-dashboard">
    <div class="inner">
        <div class="top-heading">
            <h1>Add New FAQ</h1>
        </div>

        <section class="announcement-form-area">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9">

                        <form class="ann-card" method="POST" action="{{ route('admin.faq.store') }}">
                            @csrf

                            <div class="ann-card-body">
                                <div class="row g-3">

                                    {{-- Listing select (same like announcement) --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Choose a Listing <span class="required">*</span></label>

                                            <div class="custom-select" data-select="" id="faqListingSelect">
                                                <button type="button" class="select-trigger" data-trigger="">
                                                    <span class="select-placeholder" data-label="">Select Listing</span>
                                                    <span class="select-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="m6 9 6 6 6-6"></path>
                                                        </svg>
                                                    </span>
                                                </button>

                                                <input type="hidden" name="listing_id" data-hidden id="listing_id" value="{{ old('listing_id') }}">
                                                <input type="hidden" name="listing_name" id="listing_name" value="{{ old('listing_name') }}">

                                                <div class="select-panel" data-panel="">
                                                    <ul class="select-options" data-options="" id="listingOptions">
                                                        @forelse($listings as $l)
                                                            <li class="select-option" data-id="{{ $l->id }}" data-value="{{ $l->id }}">
                                                                {{ $l->business_name }}
                                                            </li>
                                                        @empty
                                                            <li class="select-option is-disabled" style="pointer-events:none;opacity:.6">
                                                                No listing found
                                                            </li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>

                                            @error('listing_id') <div class="error-message">{{ $message }}</div> @enderror
                                            @error('listing_name') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- FAQ Repeater --}}
                                    <div class="col-md-12">
                                        <div class="ann-field">
                                            <label class="form-label">FAQ Questions <span class="required">*</span></label>
                                            <small class="text-muted d-block mb-2">Add one or multiple questions & answers</small>

                                            <div id="faqRepeater">

                                                {{-- first row --}}
                                                <div class="faq-row ann-card" style="margin-bottom:12px;">
                                                    <div class="ann-card-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Question</label>
                                                                    <input type="text" name="faq_items[0][question]" class="ann-control"
                                                                        placeholder="e.g. What areas do you serve?" value="{{ old('faq_items.0.question') }}">
                                                                    @error('faq_items.0.question') <div class="error-message">{{ $message }}</div> @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Answer</label>
                                                                    <textarea name="faq_items[0][answer]" rows="3" class="ann-control textarea-field"
                                                                        placeholder="Write answer...">{{ old('faq_items.0.answer') }}</textarea>
                                                                    @error('faq_items.0.answer') <div class="error-message">{{ $message }}</div> @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12" style="display:flex; justify-content:flex-end;">
                                                                <button type="button" class="ann-ghost-btn btn-remove-faq" style="display:none;">
                                                                    Remove
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <button type="button" id="addFaqBtn" class="ann-primary-btn">
                                                + Add More
                                            </button>

                                            @error('faq_items') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                </div>

                                <div class="ann-actions">
                                    <a href="{{ route('admin.faq.index') }}" class="ann-ghost-btn">Back</a>
                                    <div class="ann-actions-right">
                                        <a href="{{ route('admin.faq.index') }}" class="ann-ghost-btn">Cancel</a>
                                        <button type="submit" class="ann-primary-btn">Save FAQ</button>
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

    // ========= Custom Select (listing) =========
    const allSelects = document.querySelectorAll('[data-select]');
    const faqListingSelect = document.getElementById('faqListingSelect');
    const listingOptions = document.getElementById('listingOptions');

    const listingIdInput = document.getElementById('listing_id');
    const listingNameInput = document.getElementById('listing_name');

    const oldListingName = listingNameInput ? listingNameInput.value : '';
    if (oldListingName && faqListingSelect) {
        const label = faqListingSelect.querySelector('[data-label]');
        if (label) label.textContent = oldListingName;
    }

    if (faqListingSelect && listingOptions) {
        listingOptions.addEventListener('click', (e) => {
            const opt = e.target.closest('.select-option');
            if (!opt || opt.classList.contains('is-disabled')) return;

            const id = opt.dataset.id || opt.dataset.value || '';
            const text = opt.textContent.trim();

            const label = faqListingSelect.querySelector('[data-label]');
            if (label) label.textContent = text;

            if (listingIdInput) listingIdInput.value = id;
            if (listingNameInput) listingNameInput.value = text;

            faqListingSelect.classList.remove('is-open');
        });
    }

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

    // ========= FAQ Repeater =========
    const repeater = document.getElementById('faqRepeater');
    const addBtn = document.getElementById('addFaqBtn');

    function refreshRemoveButtons() {
        const rows = repeater.querySelectorAll('.faq-row');
        rows.forEach((row, idx) => {
            const btn = row.querySelector('.btn-remove-faq');
            if (!btn) return;
            btn.style.display = (rows.length > 1) ? 'block' : 'none';
        });
    }

    function reIndexNames() {
        const rows = repeater.querySelectorAll('.faq-row');
        rows.forEach((row, i) => {
            row.querySelectorAll('input, textarea').forEach(el => {
                if (el.name.includes('[question]')) el.name = `faq_items[${i}][question]`;
                if (el.name.includes('[answer]')) el.name = `faq_items[${i}][answer]`;
            });
        });
    }

    addBtn.addEventListener('click', () => {
        const rows = repeater.querySelectorAll('.faq-row');
        const newIndex = rows.length;

        const tpl = document.createElement('div');
        tpl.className = 'faq-row ann-card';
        tpl.style.marginBottom = '12px';

        tpl.innerHTML = `
            <div class="ann-card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Question</label>
                            <input type="text" name="faq_items[${newIndex}][question]" class="ann-control"
                                placeholder="e.g. Do you provide same-day service?" value="">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Answer</label>
                            <textarea name="faq_items[${newIndex}][answer]" rows="3" class="ann-control textarea-field"
                                placeholder="Write answer..."></textarea>
                        </div>
                    </div>

                    <div class="col-md-12" style="display:flex; justify-content:flex-end;">
                        <button type="button" class="ann-ghost-btn btn-remove-faq">Remove</button>
                    </div>
                </div>
            </div>
        `;

        repeater.appendChild(tpl);
        refreshRemoveButtons();
    });

    repeater.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove-faq');
        if (!btn) return;

        const row = btn.closest('.faq-row');
        if (row) row.remove();

        reIndexNames();
        refreshRemoveButtons();
    });

    refreshRemoveButtons();
});
</script>

@endsection
