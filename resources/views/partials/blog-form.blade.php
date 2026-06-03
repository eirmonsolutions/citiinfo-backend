@php
    $blog = $blog ?? null;
    $faqItems = old('faq_items');
    if ($faqItems === null) {
        $faqItems = $blog?->normalizedFaqItems() ?? [];
    }
    if ($faqItems === []) {
        $faqItems = [['question' => '', 'answer' => '']];
    }
@endphp

<div class="col-md-8">
    <div class="form-group mb-3">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title ?? '') }}" required>
        @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

<div class="col-md-4">
    <div class="form-group mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" placeholder="auto from title" value="{{ old('slug', $blog->slug ?? '') }}">
        @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

<div class="col-md-12">
    <div class="form-group mb-3">
        <label class="form-label">Short Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $blog->description ?? '') }}</textarea>
        @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group mb-3">
        <label class="form-label">Featured Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        @if(!empty($blog?->image))
        <div class="mt-2"><img src="{{ $blog->imageUrl() }}" alt="" style="max-height:120px;border-radius:8px;"></div>
        @endif
        @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group mb-3">
        <label class="form-label">Publish</label>
        <div class="form-check mt-2">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" class="form-check-input" id="is_published"
                @checked(old('is_published', $blog->is_published ?? false))>
            <label class="form-check-label" for="is_published">Published (visible on website)</label>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group mb-3">
        <label class="form-label">Inner Page Content (Rich Editor)</label>
        <textarea name="content" class="form-control rich-editor" rows="12">{{ old('content', $blog->content ?? '') }}</textarea>
        @error('content')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

<hr class="my-3">

<h5 class="mb-3">FAQ (Frequently Asked Questions)</h5>
<p class="text-muted small mb-3">Add questions and answers. FAQ JSON-LD schema is generated automatically on the public post.</p>

<div class="col-md-12">
    <div id="blogFaqRepeater">
        @foreach($faqItems as $i => $item)
        <div class="blog-faq-row border rounded p-3 mb-3">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Question</label>
                    <input type="text" name="faq_items[{{ $i }}][question]" class="form-control"
                        placeholder="e.g. What does NAP stand for in SEO?"
                        value="{{ $item['question'] ?? '' }}">
                    @error("faq_items.$i.question")<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Answer</label>
                    <textarea name="faq_items[{{ $i }}][answer]" class="form-control" rows="3"
                        placeholder="Write the answer...">{{ $item['answer'] ?? '' }}</textarea>
                    @error("faq_items.$i.answer")<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-blog-faq">Remove</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <button type="button" id="addBlogFaqBtn" class="theme-btn btn-sm">+ Add FAQ</button>
    @error('faq_items')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
</div>

<hr class="my-3">

<h5 class="mb-3">SEO / Meta</h5>

<div class="col-md-12">
    <div class="form-group mb-3">
        <label class="form-label">Meta Title</label>
        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $blog->meta_title ?? '') }}">
    </div>
</div>

<div class="col-md-12">
    <div class="form-group mb-3">
        <label class="form-label">Meta Description</label>
        <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group mb-3">
        <label class="form-label">Meta Keywords</label>
        <textarea name="meta_keywords" class="form-control" rows="2" placeholder="keyword1, keyword2">{{ old('meta_keywords', $blog->meta_keywords ?? '') }}</textarea>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group mb-3">
        <label class="form-label">Meta Image (OG)</label>
        <input type="file" name="meta_image" class="form-control" accept="image/*">
        @if(!empty($blog?->meta_image))
        <div class="mt-2"><img src="{{ $blog->metaImageUrl() }}" alt="" style="max-height:120px;border-radius:8px;"></div>
        @endif
        @error('meta_image')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
