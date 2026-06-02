@extends('layouts.admin')

@section('title', 'Listing')

@section('content')

<main class="main-dashboard">

    <div class="top-heading d-flex align-items-center justify-content-between">
        <h1 class="mb-0">Listing List</h1>

        <a href="{{ route('admin.listings.create') }}" class="btn btn-primary">
            + Add Listing
        </a>
    </div>


    <section class="table-section table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Business Name</th>
                    <th>Category</th>
                    <th>Location (City)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($listings as $i => $listing)
                <tr>
                    <td>{{ $listings->firstItem() + $i }}</td>

                    <td>{{ $listing->business_name ?? '-' }}</td>

                    <td>{{ $listing->categoryRel->name ?? '-' }}</td>

                    <td>{{ $listing->cityRel->name ?? '-' }}</td>

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
                            {{-- Edit --}}
                            <a href="{{ route('admin.listing.edit', $listing->id) }}"
                                class="btn-icon btn-edit"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                </svg>
                            </a>

                            {{-- View (optional) --}}
                            {{-- <a href="{{ route('admin.listing.view', $listing->id) }}" class="btn-icon btn-view" title="View">View</a> --}}

                            {{-- Delete (optional - soft delete) --}}

                            <form action="{{ route('admin.listing.destroy', $listing->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Are you sure?')">
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
                @empty
                <tr>
                    <td colspan="6" class="text-center">No listings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>


    </section>

</main>


<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'This listing and all related data will be permanently deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>


@endsection