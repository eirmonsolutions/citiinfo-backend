@php
    $blog = $blog ?? null;
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
