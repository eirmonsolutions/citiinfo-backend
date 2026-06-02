@extends('layouts.app')

@section('title', 'Citiinfo – Australia Business Directory | Our Blogs')

@section('meta_description', 'Read articles and guides on Citiinfo Australia business directory, local listings, and growing your business online.')

@section('meta_keywords', '')

@section('content')

<section class="banner-area-other">
    <div class="container">
        <div class="banner-text">
            <h1>Our Blogs</h1>
        </div>
    </div>
</section>

<section class="blog-section">
    <div class="container">
        <div class="row">
            @forelse($blogs as $blog)
            <div class="col-md-4 mb-4">
                <div class="blog-box">
                    <div class="blog-img">
                        @if($blog->image)
                        <img src="{{ $blog->imageUrl() }}" alt="{{ $blog->title }}">
                        @else
                        <img src="{{ asset('assets/images/blog-imgs/blog-1/img-1.jpg') }}" alt="{{ $blog->title }}">
                        @endif
                    </div>
                    <div class="blog-content">
                        <div class="post-meta">
                            <span class="item-meta post-date">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock3-icon lucide-clock-3">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6h4" />
                                </svg>
                                {{ $blog->created_at->format('F j, Y') }}
                            </span>
                        </div>
                        <h3><a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($blog->description ?: $blog->content), 160) }}</p>
                        <div class="blog-btn">
                            <a class="listing-btn" href="{{ route('blog.show', $blog->slug) }}">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p>No published blogs yet.</p>
            </div>
            @endforelse
        </div>

        @if($blogs->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $blogs->links() }}
        </div>
        @endif
    </div>
</section>

@endsection
