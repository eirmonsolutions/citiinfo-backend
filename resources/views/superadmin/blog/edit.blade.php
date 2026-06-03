@extends('layouts.superadmin')

@section('title', 'Edit Blog')

@section('content')

<main class="main-dashboard">
    <div class="top-heading">
        <h1>Edit Blog</h1>
        <a href="{{ route('superadmin.blog.index') }}" class="theme-btn">Back</a>
    </div>

    <section class="p-3">
        <form method="POST" action="{{ route('superadmin.blog.update', $blog) }}" enctype="multipart/form-data" class="row g-3">
            @csrf
            @method('PUT')
            @include('partials.blog-form', ['blog' => $blog])
            <div class="col-12">
                <button type="submit" class="theme-btn">Update Blog</button>
            </div>
        </form>
    </section>
</main>

@include('partials.rich-editor')

@push('scripts')
@include('partials.blog-faq-script')
@endpush

@endsection
