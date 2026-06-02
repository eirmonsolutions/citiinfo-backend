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
                            <a href="{{ route('blog.show', $blog->slug) }}" target="_blank">View</a>
                            @endif
                            <a href="{{ route('superadmin.blog.edit', $blog) }}">Edit</a>
                            <form method="POST" action="{{ route('superadmin.blog.destroy', $blog) }}" class="deleteBlogForm d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete">Delete</button>
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
            Swal.fire({ title: 'Delete blog?', icon: 'warning', showCancelButton: true }).then((r) => {
                if (r.isConfirmed) form.submit();
            });
        });
    });
</script>

@endsection
