@extends('layouts.app')

@section('title', $blog->meta_title ?: $blog->title)

@section('meta_description', $blog->meta_description ?: $blog->description)

@section('meta_keywords', $blog->meta_keywords ?: '')

@php $faqSchema = $blog->faqSchemaArray(); @endphp
@if($faqSchema)
@push('head')
<script type="application/ld+json">
@json($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
</script>
@endpush
@endif

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

                @php $faqs = $blog->normalizedFaqItems(); @endphp
                @if(count($faqs))
                <div class="blog-detail-faq mt-5">
                    <h3>Frequently Asked Questions (FAQs)</h3>
                    <div class="faq-list mt-4">
                        <div class="accordion" id="blogFaqAccordion">
                            @foreach($faqs as $i => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#blogFaq{{ $i }}"
                                        aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="blogFaq{{ $i }}">
                                        {{ $i + 1 }}. {{ $faq['question'] }}
                                    </button>
                                </h2>
                                <div id="blogFaq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                                    data-bs-parent="#blogFaqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-4">
                    <a class="listing-btn" href="{{ route('blog.index') }}">← Back to Blogs</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
