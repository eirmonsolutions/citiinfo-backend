@extends('layouts.superadmin')

@section('title', 'SEO Users')

@section('content')
<main class="main-dashboard">

    <div class="top-heading">
        <h1>SEO Users</h1>
        <button type="button" class="theme-btn" data-bs-toggle="modal" data-bs-target="#createSeoUserModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Create SEO User
        </button>
    </div>

    <p class="text-muted px-3 mb-0">
        SEO users can <strong>only</strong> log in and add/edit blogs. For listing owners (admin/user), use
        <a href="{{ route('superadmin.user.index') }}">Listing Users</a>.
    </p>

    <section class="table-section table-responsive mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Toggle</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                <tr>
                    <td>{{ $users->firstItem() + $i }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-label-info">SEO User</span></td>
                    <td>
                        @if($user->is_blocked)
                        <span class="badge bg-label-danger">Blocked</span>
                        @else
                        <span class="badge bg-label-success">Active</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('superadmin.seo-user.toggleStatus', $user) }}">
                            @csrf
                            @method('PATCH')
                            <label class="switch">
                                <input type="checkbox" onchange="this.form.submit()" {{ $user->is_blocked ? '' : 'checked' }}>
                                <span class="slider"></span>
                            </label>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('superadmin.seo-user.destroy', $user) }}" class="deleteSeoUserForm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No SEO users yet. Create one to let them publish blogs.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @include('partials.bootstrap-pagination', [
            'paginator' => $users,
            'ariaLabel' => 'SEO Users Pagination',
        ])
    </section>

</main>

<div class="modal fade" id="createSeoUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('superadmin.seo-user.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create SEO User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                        @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="theme-btn">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any() && old('email'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('createSeoUserModal')).show();
    });
</script>
@endif

<script>
    document.querySelectorAll('.deleteSeoUserForm').forEach((form) => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({ title: 'Delete SEO user?', icon: 'warning', showCancelButton: true }).then((r) => {
                if (r.isConfirmed) form.submit();
            });
        });
    });
</script>

@endsection
