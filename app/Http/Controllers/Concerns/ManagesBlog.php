<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait ManagesBlog
{
    protected function blogRules(?Blog $blog = null): array
    {
        $slugRule = 'nullable|string|max:255|unique:blogs,slug';
        if ($blog) {
            $slugRule .= ',' . $blog->id;
        }

        return [
            'title'             => ['required', 'string', 'max:255'],
            'slug'              => $slugRule,
            'description'       => ['nullable', 'string'],
            'content'           => ['nullable', 'string'],
            'image'             => ['nullable', 'image', 'max:5120'],
            'meta_title'        => ['nullable', 'string', 'max:255'],
            'meta_description'  => ['nullable', 'string'],
            'meta_keywords'     => ['nullable', 'string'],
            'meta_image'        => ['nullable', 'image', 'max:5120'],
            'faq_items'         => ['nullable', 'array'],
            'faq_items.*.question' => ['nullable', 'string', 'max:500'],
            'faq_items.*.answer'   => ['nullable', 'string'],
            'is_published'      => ['nullable', 'boolean'],
        ];
    }

    protected function normalizeFaqItemsInput(?array $items): ?array
    {
        if ($items === null) {
            return null;
        }

        $normalized = collect($items)
            ->map(fn ($item) => [
                'question' => trim((string) ($item['question'] ?? '')),
                'answer'   => trim((string) ($item['answer'] ?? '')),
            ])
            ->filter(fn ($item) => $item['question'] !== '' && $item['answer'] !== '')
            ->values()
            ->all();

        return $normalized === [] ? null : $normalized;
    }

    protected function saveBlog(Blog $blog, Request $request, int $userId): void
    {
        $slugBase = $request->filled('slug') ? $request->input('slug') : $request->input('title');
        $slug = Blog::uniqueSlug($slugBase, $blog->exists ? $blog->id : null);

        $blog->fill([
            'user_id'           => $userId,
            'title'             => $request->input('title'),
            'slug'              => $slug,
            'description'       => $request->input('description'),
            'content'           => $request->input('content'),
            'meta_title'        => $request->input('meta_title'),
            'meta_description'  => $request->input('meta_description'),
            'meta_keywords'     => $request->input('meta_keywords'),
            'faq_items'         => $this->normalizeFaqItemsInput($request->input('faq_items')),
            'is_published'      => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $blog->image = Blog::storeImage($request->file('image'), 'blogs');
        }

        if ($request->hasFile('meta_image')) {
            if ($blog->meta_image) {
                Storage::disk('public')->delete($blog->meta_image);
            }
            $blog->meta_image = Blog::storeImage($request->file('meta_image'), 'blogs/meta');
        }

        $blog->save();
    }
}
