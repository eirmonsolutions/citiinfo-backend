@extends('layouts.admin')

@section('title', 'Events')

@section('content')
<main class="main-dashboard">

    @if($events->count() == 0)
    <section class="announcement-area">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="announcement-inner">
                    <div class="announcement-img">
                        <img loading="lazy" src="{{ asset('assets/images/trophy-img.png') }}" alt="">
                    </div>
                    <div class="announcement-content">
                        <h2>No events yet!</h2>
                        <p>Create your first event from the button below.</p>
                        <a href="{{ route('admin.event.create') }}" class="announcement-btn">
                            <span>+</span> Add New Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else

    <div class="announcement-table">
        <div class="top-heading">
            <h1>Event List</h1>
            <a href="{{ route('admin.event.create') }}" class="theme-btn">+ Add Event</a>
        </div>

        <section class="table-section table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Business</th>
                        <th>Title</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($events as $i => $e)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $e->listing->business_name ?? $e->listing_name ?? '-' }}</td>
                        <td>{{ $e->title }}</td>
                        <td>{{ optional($e->start_date)->format('Y-m-d') }} {{ $e->start_time }}</td>
                        <td>{{ optional($e->end_date)->format('Y-m-d') }} {{ $e->end_time }}</td>

                        <td>
                            <label class="switch">
                                <input type="checkbox"
                                    class="status-toggle"
                                    data-url="{{ route('admin.event.toggle', $e->id) }}"
                                    {{ $e->is_active ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </td>

                        <td>
                            <div class="action-buttons" style="display:flex; gap:10px; align-items:center;">
                                <a href="{{ route('admin.event.edit', $e->id) }}" class="btn-icon btn-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('admin.event.destroy', $e->id) }}"
                                    onsubmit="return confirm('Delete this event?')">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-toggle').forEach((toggle) => {
            toggle.addEventListener('change', async function() {
                const url = this.dataset.url;
                const isActive = this.checked ? 1 : 0;
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
                        this.checked = !this.checked;
                    } else {
                        const data = await res.json();
                        this.checked = data.is_active == 1;
                    }
                } catch (e) {
                    this.checked = !this.checked;
                } finally {
                    this.disabled = false;
                }
            });
        });
    });
</script>
@endsection