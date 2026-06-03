@extends('layouts.user')

@section('title', 'Add Blog')

@section('content')

<main class="main-dashboard">
    <div class="top-heading">
        <h1>Add Blog</h1>
        <a href="{{ route('blog.dashboard') }}" class="theme-btn">Back</a>
    </div>

    <section class="p-3">
        <form method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data" class="row g-3">
            @csrf
            @include('partials.blog-form')
            <div class="col-12">
                <button type="submit" class="theme-btn">Save Blog</button>
            </div>
        </form>
    </section>
</main>

@include('partials.rich-editor')

@push('scripts')
@include('partials.blog-faq-script')
@endpush

@endsection
