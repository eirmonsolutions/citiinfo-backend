@extends('layouts.admin')

@section('title', 'Announcement')

@section('content')
<main class="main-dashboard">

    {{-- ✅ If NO announcements, show trophy section --}}
    @if($announcements->count() == 0)

    <section class="announcement-area">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="announcement-inner">
                    <div class="announcement-img">
                        <img loading="lazy" src="{{ asset('assets/images/trophy-img.png') }}" alt="">
                    </div>
                    <div class="announcement-content">
                        <h2>Nothing but this golden trophy!</h2>
                        <p>You must be here for the first time. If you like to add some thing, click the button below.</p>
                        <a href="{{ route('admin.announcement.create') }}" id="showFormBtn" class="announcement-btn">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-plus-icon lucide-plus">
                                    <path d="M5 12h14" />
                                    <path d="M12 5v14" />
                                </svg>
                            </span>
                            Add New Announcement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ✅ Table display none when no announcement --}}
    <div class="announcement-table" style="display:none;"></div>

    @else

    {{-- ✅ If announcements exist, show table --}}
    <div class="announcement-table">

        <div class="top-heading">
            <h1>Announcement List</h1>

            <a href="{{ route('admin.announcement.create') }}" class="theme-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Announcement
            </a>
        </div>

        <section class="table-section table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Business Name</th>
                        <th>Icon</th>
                        <th>Title</th>
                        <!-- <th>Description</th> -->
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($announcements as $index => $a)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ $a->listing->business_name ?? '-' }}
                        </td>


                        <td>
                            @if($a->icon)

                            <span class="lucide-icon-wrap">
                                <i data-lucide="{{ $a->icon }}"></i>
                            </span>
                            @else
                            -
                            @endif
                        </td>

                        <td>{{ $a->title ?? '-' }}</td>

                        <!-- <td style="max-width:260px;">
                            {{ \Illuminate\Support\Str::limit($a->description ?? '-', 80) }}
                        </td> -->

                        <td>{{ $a->start_date ? \Carbon\Carbon::parse($a->start_date)->format('Y-m-d') : '-' }}</td>
                        <td>{{ $a->end_date ? \Carbon\Carbon::parse($a->end_date)->format('Y-m-d') : '-' }}</td>

                        {{-- ✅ Status Toggle (same page ON/OFF via AJAX) --}}
                        <td>
                            <label class="switch">
                                <input
                                    type="checkbox"
                                    class="status-toggle"
                                    data-url="{{ route('admin.announcement.toggle', $a->id) }}"
                                    {{ !empty($a->is_active) ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </td>

                        {{-- ✅ Actions --}}
                        <td>
                            <div class="action-buttons" style="display:flex; gap:10px; align-items:center;">
                                <a href="{{ route('admin.announcement.edit', $a->id) }}" class="btn-icon btn-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('admin.announcement.destroy', $a->id) }}"
                                    onsubmit="return confirm('Delete this announcement?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18" />
                                            <path d="M8 6V4h8v2" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </section>

    </div>

    @endif

</main>

{{-- ✅ JS: status toggle + lucide icons --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        if (window.lucide && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }

        document.querySelectorAll('.status-toggle').forEach((toggle) => {
            toggle.addEventListener('change', async function() {

                const url = this.dataset.url;
                const isActive = this.checked ? 1 : 0;

                // prevent double click while saving
                this.disabled = true;

                try {
                    const res = await fetch(url, {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json",
                        },
                        body: JSON.stringify({
                            is_active: isActive
                        })
                    });

                    if (!res.ok) {
                        this.checked = !this.checked; // revert
                    } else {
                        const data = await res.json();
                        // force UI to match server
                        this.checked = data.is_active == 1;
                    }

                } catch (e) {
                    this.checked = !this.checked; // revert
                } finally {
                    this.disabled = false;
                }
            });
        });

    });
</script>


@endsection