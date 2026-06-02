@extends('layouts.user')

@section('title', 'Edit Blog')

@section('content')

<main class="main-dashboard">
    <div class="top-heading">
        <h1>Edit Blog</h1>
        <a href="{{ route('blog.dashboard') }}" class="theme-btn">Back</a>
    </div>

    <section class="p-3">
        <form method="POST" action="{{ route('blog.update', $blog) }}" enctype="multipart/form-data" class="row g-3">
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

@endsection
