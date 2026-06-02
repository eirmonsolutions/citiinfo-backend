@extends('layouts.app')

@section('title', $blog->meta_title ?: $blog->title)

@section('meta_description', $blog->meta_description ?: $blog->description)

@section('meta_keywords', $blog->meta_keywords ?: '')

@section('content')

<section class="banner-area-other">
    <div class="container">
        <div class="banner-text">
            <h1>{{ $blog->title }}</h1>
        </div>
    </div>
</section>

<section class="blog-section blog-detail-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($blog->image)
                <div class="blog-detail-img mb-4">
                    <img src="{{ $blog->imageUrl() }}" alt="{{ $blog->title }}" class="w-100" style="border-radius:12px;">
                </div>
                @endif

                <div class="post-meta mb-3">
                    <span class="item-meta post-date">{{ $blog->created_at->format('F j, Y') }}</span>
                </div>

                @if($blog->description)
                <p class="lead">{{ $blog->description }}</p>
                @endif

                <div class="blog-inner-content">
                    {!! $blog->content !!}
                </div>

                <div class="mt-4">
                    <a class="listing-btn" href="{{ route('blog.index') }}">← Back to Blogs</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
