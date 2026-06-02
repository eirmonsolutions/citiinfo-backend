<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'content',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: 'blog';
        $original = $slug;
        $counter = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public static function storeImage(?UploadedFile $file, string $folder = 'blogs'): ?string
    {
        if (!$file) {
            return null;
        }

        return $file->store($folder, 'public');
    }

    public function imageUrl(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function metaImageUrl(): ?string
    {
        return $this->meta_image ? Storage::url($this->meta_image) : null;
    }

    public function toApiArray(bool $detailed = false): array
    {
        $data = [
            'id'                 => $this->id,
            'user_id'            => $this->user_id,
            'title'              => $this->title,
            'slug'               => $this->slug,
            'description'        => $this->description,
            'image'              => $this->image,
            'image_url'          => $this->imageUrl() ? url($this->imageUrl()) : null,
            'meta_title'         => $this->meta_title,
            'meta_description'   => $this->meta_description,
            'meta_keywords'      => $this->meta_keywords,
            'meta_image'         => $this->meta_image,
            'meta_image_url'     => $this->metaImageUrl() ? url($this->metaImageUrl()) : null,
            'is_published'       => (bool) $this->is_published,
            'public_url'         => url('/blog/post/' . $this->slug),
            'created_at'         => $this->created_at?->toIso8601String(),
            'updated_at'         => $this->updated_at?->toIso8601String(),
        ];

        if ($this->relationLoaded('user') && $this->user) {
            $data['author'] = [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ];
        }

        if ($detailed) {
            $data['content'] = $this->content;
        }

        return $data;
    }
}
