<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class VillagePost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'type',
        'category',
        'excerpt',
        'content',
        'image_path',
        'is_published',
        'published_at',
        'user_id',
        'author_name',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->published()->orderByDesc('published_at');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function authorDisplayName(): string
    {
        return $this->author_name
            ?? $this->author?->name
            ?? 'Redaksi Desa';
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'assets/')) {
            return asset($this->image_path);
        }

        return '/storage/'.ltrim($this->image_path, '/');
    }

    public function coverUrl(): string
    {
        return $this->imageUrl()
            ?? asset('assets/media/images/2600x1200/1.png');
    }

    public function typeLabel(): string
    {
        return $this->type === 'pengumuman' ? 'Pengumuman' : 'Berita';
    }

    public function formattedDate(): string
    {
        return $this->published_at?->translatedFormat('d F Y') ?? $this->created_at->translatedFormat('d F Y');
    }

    public function relatedPosts(int $limit = 5): Collection
    {
        $excludeIds = [$this->id];

        $related = static::query()
            ->published()
            ->where('id', '!=', $this->id)
            ->where('type', $this->type)
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();

        $excludeIds = array_merge($excludeIds, $related->pluck('id')->all());

        if ($related->count() < $limit) {
            $related = $related->concat(
                static::query()
                    ->published()
                    ->whereNotIn('id', $excludeIds)
                    ->where('type', $this->type)
                    ->orderByDesc('published_at')
                    ->limit($limit - $related->count())
                    ->get()
            );

            $excludeIds = array_merge($excludeIds, $related->pluck('id')->all());
        }

        if ($related->count() < $limit) {
            $related = $related->concat(
                static::query()
                    ->published()
                    ->whereNotIn('id', $excludeIds)
                    ->orderByDesc('published_at')
                    ->limit($limit - $related->count())
                    ->get()
            );
        }

        return $related->take($limit);
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $counter = 1;

        while (static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $original.'-'.$counter++;
        }

        return $slug;
    }
}
