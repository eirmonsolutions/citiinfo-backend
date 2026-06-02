@extends('layouts.admin')

@section('title', 'Edit Announcement')

@section('content')

<main class="main-dashboard">
    <div class="inner">
        <div class="top-heading">
            <h1>Edit Announcement</h1>
        </div>

        <section class="announcement-form-area">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9">

                        <!-- Live Preview -->
                        <div class="ann-card ann-preview">
                            <div class="ann-card-head">Live Preview</div>
                            <div class="ann-card-body">
                                <div class="ann-preview-icon" id="pvIconWrap">
                                    <i data-lucide="{{ old('icon', $announcement->icon ?? 'megaphone') }}"></i>
                                </div>
                                <div class="ann-preview-texts">
                                    <div id="pvTitle" class="ann-preview-title">{{ old('title', $announcement->title) }}</div>
                                    <div id="pvDesc" class="ann-preview-desc">{{ old('description', $announcement->description) }}</div>
                                </div>
                                <span id="pvBtn" class="ann-chip">{{ old('button_text', $announcement->button_text ?? 'Announcement') }}</span>
                            </div>
                        </div>

                        <!-- Form card -->
                        <form id="annForm" class="ann-card" method="POST" action="{{ route('admin.announcement.update', $announcement) }}">
                            @csrf
                            @method('PUT')

                            <div class="ann-card-body">
                                <div class="row g-3">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Choose a Listing <span class="required">*</span></label>

                                            <div class="custom-select" data-select="" id="announcementSelect">
                                                <button type="button" class="select-trigger" data-trigger="">
                                                    <span class="select-placeholder" data-label="">
                                                        {{ old('listing_name', $announcement->listing_name ?? 'Select Listing') }}
                                                    </span>
                                                    <span class="select-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="m6 9 6 6 6-6"></path>
                                                        </svg>
                                                    </span>
                                                </button>

                                                {{-- ✅ Hidden inputs for DB --}}
                                                <input type="hidden" name="listing_id" data-hidden id="listing_id"
                                                    value="{{ old('listing_id', $announcement->listing_id) }}">
                                                <input type="hidden" name="listing_name" id="listing_name"
                                                    value="{{ old('listing_name', $announcement->listing_name) }}">

                                                <div class="select-panel" data-panel="">
                                                    <ul class="select-options" data-options="" id="categoryOptions">
                                                        @forelse($listings as $l)
                                                        <li class="select-option"
                                                            data-id="{{ $l->id }}"
                                                            data-value="{{ $l->id }}">
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

                                            @error('listing_name')
                                            <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <!-- Icon Picker -->
                                        <div class="ann-field">
                                            <label class="form-label">Choose An Icon <span class="required">*</span></label>
                                            <small class="text-muted d-block mb-2">Pick an icon that best represents your announcement</small>

                                            <input type="hidden" id="fIcon" name="icon"
                                                value="{{ old('icon', $announcement->icon ?? 'megaphone') }}">

                                            <div class="icon-grid" role="listbox" aria-label="Announcement icons">
                                                <button type="button" class="icon-chip" title="Megaphone"><i data-lucide="megaphone"></i></button>
                                                <button type="button" class="icon-chip" title="Star"><i data-lucide="star"></i></button>
                                                <button type="button" class="icon-chip" title="Tag"><i data-lucide="tag"></i></button>
                                                <button type="button" class="icon-chip" title="Gift"><i data-lucide="gift"></i></button>
                                                <button type="button" class="icon-chip" title="Heart"><i data-lucide="heart"></i></button>
                                                <button type="button" class="icon-chip" title="Bell"><i data-lucide="bell"></i></button>
                                                <!-- add more icons here -->
                                            </div>

                                            @error('icon')
                                            <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Call To Action Title <span class="required">*</span></label>
                                            <input id="fTitle" name="title" type="text" class="ann-control"
                                                placeholder="e.g. 46% Off - Two Vouchers Each Valid for One Month"
                                                value="{{ old('title', $announcement->title) }}">
                                            @error('title')
                                            <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Description</label>
                                            <textarea id="fDesc" name="description" rows="4" class="ann-control textarea-field"
                                                placeholder="We are proud to announce launch of new branch">{{ old('description', $announcement->description) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Button Text</label>
                                            <input id="fBtnText" name="button_text" type="text" class="ann-control"
                                                placeholder="Announcement"
                                                value="{{ old('button_text', $announcement->button_text ?? 'Announcement') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Button Link</label>
                                            <input id="fBtnLink" name="button_link" type="url" class="ann-control"
                                                placeholder="https://"
                                                value="{{ old('button_link', $announcement->button_link) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="ann-label">Start Date</label>
                                            <input id="fStartDate" name="start_date" type="date" class="ann-control"
                                                value="{{ old('start_date', optional($announcement->start_date)->format('Y-m-d')) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="ann-label">End Date</label>
                                            <input id="fEndDate" name="end_date" type="date" class="ann-control"
                                                value="{{ old('end_date', optional($announcement->end_date)->format('Y-m-d')) }}">
                                        </div>
                                    </div>

                                    {{-- ✅ Status checkbox (store/update me supported) --}}
                                    <div class="col-md-12">
                                        <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                                            <label class="form-label" style="margin:0;">Status</label>
                                            <input type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                                            <span>{{ old('is_active', $announcement->is_active) ? 'Active' : 'InActive' }}</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="ann-actions">
                                    <a href="{{ route('admin.announcement.index') }}" id="backToHero" class="ann-ghost-btn">
                                        Back
                                    </a>
                                    <div class="ann-actions-right">
                                        <a href="{{ route('admin.announcement.index') }}" class="ann-ghost-btn">Cancel</a>
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
        if (window.lucide) lucide.createIcons();

        // ========= Custom Select (listing) =========
        const allSelects = document.querySelectorAll('[data-select]');
        const announcementSelect = document.getElementById('announcementSelect');
        const categoryOptions = document.getElementById('categoryOptions');

        const listingIdInput = document.getElementById('listing_id');
        const listingNameInput = document.getElementById('listing_name');

        if (announcementSelect && categoryOptions) {
            categoryOptions.addEventListener('click', (e) => {
                const opt = e.target.closest('.select-option');
                if (!opt || opt.classList.contains('is-disabled')) return;

                const id = opt.dataset.id || opt.dataset.value || '';
                const text = opt.textContent.trim();

                const label = announcementSelect.querySelector('[data-label]');
                if (label) label.textContent = text;

                if (listingIdInput) listingIdInput.value = id;
                if (listingNameInput) listingNameInput.value = text;

                announcementSelect.classList.remove('is-open');
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

        // ========= Live Preview =========
        const pvTitle = document.getElementById('pvTitle');
        const pvDesc = document.getElementById('pvDesc');
        const pvBtn = document.getElementById('pvBtn');
        const pvIconWrap = document.getElementById('pvIconWrap');

        const fTitle = document.getElementById('fTitle');
        const fDesc = document.getElementById('fDesc');
        const fBtnText = document.getElementById('fBtnText');
        const fIcon = document.getElementById('fIcon');

        function updatePreview() {
            if (pvTitle && fTitle) pvTitle.textContent = fTitle.value || '—';
            if (pvDesc && fDesc) pvDesc.textContent = fDesc.value || '—';
            if (pvBtn && fBtnText) pvBtn.textContent = fBtnText.value || '—';
        }

        [fTitle, fDesc, fBtnText].forEach(el => {
            if (!el) return;
            el.addEventListener('input', updatePreview);
        });

        // ========= Icon picker =========
        function setSelectedIcon(iconName) {
            if (!iconName) iconName = 'megaphone';

            document.querySelectorAll('.icon-grid .icon-chip').forEach(b => b.classList.remove('is-selected'));

            const btn = Array.from(document.querySelectorAll('.icon-grid .icon-chip'))
                .find(b => (b.querySelector('[data-lucide]')?.getAttribute('data-lucide') === iconName));

            if (btn) btn.classList.add('is-selected');

            if (fIcon) fIcon.value = iconName;

            if (pvIconWrap) {
                pvIconWrap.innerHTML = `<i data-lucide="${iconName}"></i>`;
                if (window.lucide) lucide.createIcons();
            }
        }

        setSelectedIcon(fIcon ? fIcon.value : 'megaphone');
        updatePreview();

        document.querySelectorAll('.icon-grid .icon-chip').forEach(btn => {
            btn.addEventListener('click', () => {
                const iconEl = btn.querySelector('[data-lucide]');
                const iconName = iconEl ? iconEl.getAttribute('data-lucide') : 'megaphone';
                setSelectedIcon(iconName);
            });
        });
    });
</script>

@endsection