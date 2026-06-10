@extends('layouts.superadmin')

@section('title', 'Listing')

@section('content')

<main class="main-dashboard">

    <div class="top-heading">
        <h1>Listing List</h1>
    </div>

    {{-- FILTER BAR --}}
    <div class="d-flex gap-2 flex-wrap mb-3">

        <a href="{{ route('superadmin.listing.index', ['status' => 'all']) }}"
            class="theme-btn {{ $status == 'all' ? 'active' : '' }}">
            All ({{ $counts['all'] ?? 0 }})
        </a>

        <a href="{{ route('superadmin.listing.index', ['status' => 'published']) }}"
            class="theme-btn {{ $status == 'published' ? 'active' : '' }}">
            Published ({{ $counts['published'] ?? 0 }})
        </a>

        <a href="{{ route('superadmin.listing.index', ['status' => 'pending']) }}"
            class="theme-btn {{ $status == 'pending' ? 'active' : '' }}">
            Pending ({{ $counts['pending'] ?? 0 }})
        </a>

        <a href="{{ route('superadmin.listing.index', ['status' => 'expired']) }}"
            class="theme-btn {{ $status == 'expired' ? 'active' : '' }}">
            Expired ({{ $counts['expired'] ?? 0 }})
        </a>

        <a href="{{ route('superadmin.listing.index', ['status' => 'trash']) }}"
            class="theme-btn {{ $status == 'trash' ? 'active' : '' }}">
            Trash ({{ $counts['trash'] ?? 0 }})
        </a>




    </div>

    <section class="table-section table-responsive">
        <table class="table">
            <thead>
                <tr>
                    @if(in_array($status, ['all','published']))
                    <th>Allow</th>
                    <th>Homepage (Max 6)</th>
                    @endif
                    <th>#</th>
                    <th>Business Name</th>
                    <th>Category</th>
                    <th>Location (City)</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($listings as $i => $listing)
                <tr>

                    @if(in_array($status, ['all','published']))
                    <td>
                        <form method="POST" action="{{ route('superadmin.listing.toggleAllow', $listing) }}">
                            @csrf
                            @method('PATCH')

                            <label class="switch">
                                <input type="checkbox" onchange="this.form.submit()" {{ $listing->is_allowed ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </form>
                    </td>

                    <td>
                        <form method="POST" action="{{ route('superadmin.listing.toggleHomepage', $listing) }}">
                            @csrf
                            @method('PATCH')

                            <label class="switch">
                                <input type="checkbox"
                                    onchange="this.form.submit()"
                                    {{ $listing->show_on_homepage ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </form>
                    </td>
                    @endif

                    <td>{{ $listings->firstItem() + $i }}</td>

                    <td>
                        {{ $listing->business_name ?? '-' }}
                    </td>

                    <td>
                        {{ $listing->categoryRel->name ?? '-' }}
                    </td>

                    <td>
                        {{ $listing->cityRel->name ?? '-' }}
                    </td>

                    <td>
                        {{ $listing->created_at ? $listing->created_at->format('d M Y') : '-' }}
                    </td>

                    <td>
                        @php
                        $st = $listing->status ?? 'pending';
                        $badgeClass = match($st) {
                        'published' => 'bg-label-success',
                        'pending' => 'bg-label-warning',
                        'expired' => 'bg-label-danger',
                        'trash' => 'bg-label-danger',
                        default => 'bg-label-secondary',
                        };
                        @endphp

                        <span class="badge {{ $badgeClass }}">
                            {{ ucfirst($st) }}
                        </span>
                    </td>

                    <td>
                        <div class="action-buttons">

                            {{-- Pending -> View + Approve --}}
                            @if(($listing->status ?? 'pending') === 'pending' && !$listing->trashed())

                            {{-- View --}}
                            <a href="{{ route('superadmin.listing.view', $listing->id) }}"
                                class="btn-icon btn-edit"
                                title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>

                            
                            {{-- Approve --}}
                            <form method="POST"
                                action="{{ route('superadmin.listing.approve', $listing->id) }}"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="theme-btn"
                                    style="padding:8px 14px;">
                                    Approve
                                </button>
                            </form>


                            {{-- If in Trash -> Restore button --}}
                            @elseif($listing->trashed())
                            <form method="POST" action="{{ route('superadmin.listing.restore', $listing->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="theme-btn" style="padding:8px 14px;">Restore</button>
                            </form>

                            {{-- Published/All/Expired -> View (A TAG) + Delete(soft) --}}
                            @else
                            {{-- View --}}
                            <a href="{{ route('superadmin.listing.view', $listing->id) }}" class="btn-icon btn-edit" title="View">
                                {{-- Eye icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>

                            <a href="{{ route('superadmin.listing.edit', $listing->id) }}"
                                class="btn-icon btn-edit"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                </svg>
                            </a>

                            {{-- Delete => Trash (soft delete) --}}
                            <form method="POST" action="{{ route('superadmin.listing.destroy', $listing->id) }}" class="deleteListingForm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </button>
                            </form>
                            @endif

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="{{ in_array($status, ['all','published']) ? 9 : 7 }}" class="text-center">
                        No listings found.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

        @if($listings->hasPages())
        <div class="pagination-wrap">
            <nav aria-label="Listing Pagination">
                <ul class="pagination">

                    {{-- Previous --}}
                    <li class="page-item {{ $listings->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ $listings->previousPageUrl() ?? '#' }}">
                            &laquo;
                        </a>
                    </li>

                    {{-- Pages --}}
                    @foreach ($listings->getUrlRange(1, $listings->lastPage()) as $page => $url)
                    <li class="page-item {{ $listings->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach

                    {{-- Next --}}
                    <li class="page-item {{ $listings->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link"
                            href="{{ $listings->nextPageUrl() ?? '#' }}">
                            &raquo;
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
        @endif


    </section>

</main>

@endsection