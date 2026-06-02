@extends('layouts.admin')

@section('title', "FAQ's")

@section('content')
<main class="main-dashboard">

    @if($faqs->count() == 0)

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
                        <a href="{{ route('admin.faq.create') }}" class="announcement-btn">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="M12 5v14" />
                                </svg>
                            </span>
                            Add New FAQ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="announcement-table" style="display:none;"></div>

    @else

    <div class="announcement-table">

        <div class="top-heading">
            <h1>FAQ List</h1>

            <a href="{{ route('admin.faq.create') }}" class="theme-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add FAQ
            </a>
        </div>

        <section class="table-section table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Business Name</th>
                        <th>Total Questions</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($faqs as $index => $f)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $f->listing->business_name ?? $f->listing_name ?? '-' }}</td>
                        <td>{{ $f->items->count() }}</td>
                        <td>{{ $f->created_at ? $f->created_at->format('Y-m-d') : '-' }}</td>

                        <td>
                            <div class="action-buttons" style="display:flex; gap:10px; align-items:center;">
                                <a href="{{ route('admin.faq.edit', $f->id) }}" class="btn-icon btn-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('admin.faq.destroy', $f->id) }}"
                                    onsubmit="return confirm('Delete this FAQ?')">
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
@endsection