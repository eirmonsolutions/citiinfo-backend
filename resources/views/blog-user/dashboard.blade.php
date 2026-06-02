@extends('layouts.user')

@section('title', 'My Blogs')

@section('content')

<main class="main-dashboard">
    <div class="top-heading">
        <h1>My Blogs</h1>
        <a href="{{ route('blog.create') }}" class="theme-btn">Add Blog</a>
    </div>

    <section class="table-section table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
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
                    <td><code>{{ $blog->slug }}</code></td>
                    <td>
                        @if($blog->is_published)
                        <span class="badge bg-label-success">Published</span>
                        @else
                        <span class="badge bg-label-warning">Draft</span>
                        @endif
                    </td>
                    <td>
                        @if($blog->is_published)
                        <a href="{{ route('blog.show', $blog->slug) }}" target="_blank">View</a>
                        @endif
                        <a href="{{ route('blog.edit', $blog) }}">Edit</a>
                        <form method="POST" action="{{ route('blog.destroy', $blog) }}" class="deleteBlogForm d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-0">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No blogs yet. Click Add Blog to create your first post.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($blogs->hasPages())
        <div class="mt-3">{{ $blogs->links() }}</div>
        @endif
    </section>
</main>

<script>
    document.querySelectorAll('.deleteBlogForm').forEach((form) => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({ title: 'Delete?', icon: 'warning', showCancelButton: true }).then((r) => {
                if (r.isConfirmed) form.submit();
            });
        });
    });
</script>

@endsection
