@extends('layouts.admin')
@section('title', 'Edit Coupon')

@section('content')
<main class="main-dashboard">
    <div class="inner">
        <div class="top-heading">
            <h1>Edit Coupon</h1>
        </div>

        <section class="announcement-form-area">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">

                        <form class="ann-card" method="POST" action="{{ route('admin.coupon.update', $coupon->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="ann-card-body">
                                <div class="row g-3">

                                    {{-- Listing --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Choose a listing for the coupon <span class="required">*</span></label>

                                            <div class="custom-select" data-select id="couponListingSelect">
                                                <button type="button" class="select-trigger" data-trigger>
                                                    <span class="select-placeholder" data-label>Select Listing</span>
                                                    <span class="select-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="m6 9 6 6 6-6"></path>
                                                        </svg>
                                                    </span>
                                                </button>

                                                <input type="hidden" name="listing_id" id="listing_id"
                                                    value="{{ old('listing_id', $coupon->listing_id) }}">
                                                <input type="hidden" name="listing_name" id="listing_name"
                                                    value="{{ old('listing_name', $coupon->listing_name) }}">

                                                <div class="select-panel" data-panel>
                                                    <ul class="select-options" data-options id="listingOptions">
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

                                    {{-- Title --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Coupon Title <span class="required">*</span></label>
                                            <input type="text" name="title" class="ann-control"
                                                value="{{ old('title', $coupon->title) }}">
                                            @error('title') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Code --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Coupon Code <span class="required">*</span></label>
                                            <input type="text" name="code" class="ann-control"
                                                value="{{ old('code', $coupon->code) }}">
                                            @error('code') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Discount --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Discount Value <span class="required">*</span></label>
                                            <input type="number" step="0.01" name="discount_value" class="ann-control"
                                                value="{{ old('discount_value', $coupon->discount_value) }}">
                                            @error('discount_value') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Start date --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Coupon Start Date<span class="required">*</span></label>
                                            <input type="date" name="start_date" class="ann-control"
                                                value="{{ old('start_date', optional($coupon->start_date)->format('d-m-y')) }}">
                                            @error('start_date') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Start time --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Coupon End Date <span class="required">*</span></label>
                                            <input type="date" name="end_date" class="ann-control"
                                                value="{{ old('end_date', optional($coupon->end_date)->format('d-m-y')) }}" required>
                                            @error('end_date') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Details --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Coupon Details <span class="required">*</span></label>
                                            <textarea name="details" rows="4" class="ann-control textarea-field">{{ old('details', $coupon->details) }}</textarea>
                                            @error('details') <div class="error-message">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Featured Image --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Coupon Featured Image</label>
                                            <input type="file" name="featured_image" class="ann-control">
                                            @error('featured_image') <div class="error-message">{{ $message }}</div> @enderror

                                            @if(!empty($coupon->featured_image))
                                            <div style="margin-top:8px;">
                                                <small>Current:</small><br>
                                                <img src="{{ asset('storage/'.$coupon->featured_image) }}" style="max-height:80px;border-radius:6px;">
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="ann-actions">
                                    <a href="{{ route('admin.coupon.index') }}" class="ann-ghost-btn">Back</a>
                                    <div class="ann-actions-right">
                                        <a href="{{ route('admin.coupon.index') }}" class="ann-ghost-btn">Cancel</a>
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

        const listingSelect = document.getElementById('couponListingSelect');
        const listingOptions = document.getElementById('listingOptions');
        const listingIdInput = document.getElementById('listing_id');
        const listingNameInput = document.getElementById('listing_name');

        // set old selected name label
        const oldListingName = listingNameInput ? listingNameInput.value : '';
        if (oldListingName && listingSelect) {
            const label = listingSelect.querySelector('[data-label]');
            if (label) label.textContent = oldListingName;
        }

        if (listingSelect && listingOptions) {
            listingOptions.addEventListener('click', (e) => {
                const opt = e.target.closest('.select-option');
                if (!opt || opt.classList.contains('is-disabled')) return;

                const id = opt.dataset.id || '';
                const text = opt.textContent.trim();

                const label = listingSelect.querySelector('[data-label]');
                if (label) label.textContent = text;

                if (listingIdInput) listingIdInput.value = id;
                if (listingNameInput) listingNameInput.value = text;

                listingSelect.classList.remove('is-open');
            });
        }
    });
</script>
@endsection