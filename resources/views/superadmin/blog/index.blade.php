@extends('layouts.superadmin')

@section('title', 'Blogs')

@section('content')

<main class="main-dashboard">

    <div class="top-heading">
        <h1>All Blogs</h1>
        <a href="{{ route('superadmin.blog.create') }}" class="theme-btn">+ Add Blog</a>
    </div>

    <p class="text-muted px-3 mb-0">
        Superadmin and <a href="{{ route('superadmin.seo-user.index') }}">SEO Users</a> can both add blogs.
        Listing users are managed separately under Listing Users.
    </p>

    <section class="table-section table-responsive mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $i => $blog)
                <tr>
                    <td>{{ $blogs->firstItem() + $i }}</td>
                    <td>{{ $blog->title }}</td>
                    <td>{{ $blog->user->name ?? '—' }}</td>
                    <td><code>{{ $blog->slug }}</code></td>
                    <td>
                        @if($blog->is_published)
                        <span class="badge bg-label-success">Published</span>
                        @else
                        <span class="badge bg-label-warning">Draft</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            @if($blog->is_published)
                            <a href="{{ route('blog.show', $blog->slug) }}" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>
                            @endif
                            <a href="{{ route('superadmin.blog.edit', $blog) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('superadmin.blog.destroy', $blog) }}" class="deleteBlogForm d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete">
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
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No blogs yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @include('partials.bootstrap-pagination', [
        'paginator' => $blogs,
        'ariaLabel' => 'Blogs Pagination',
        ])
    </section>

</main>

<script>
    document.querySelectorAll('.deleteBlogForm').forEach((form) => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Delete blog?',
                icon: 'warning',
                showCancelButton: true
            }).then((r) => {
                if (r.isConfirmed) form.submit();
            });
        });
    });
</script>

@endsection