<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VillageUmkm extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'owner_name',
        'excerpt',
        'description',
        'image_path',
        'phone',
        'whatsapp',
        'address',
        'products',
        'is_published',
        'is_featured',
        'published_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    public static function categories(): array
    {
        return [
            'makanan' => 'Makanan & Minuman',
            'kerajinan' => 'Kerajinan',
            'jasa' => 'Jasa',
            'pertanian' => 'Pertanian',
            'perdagangan' => 'Perdagangan',
            'lainnya' => 'Lainnya',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->published()
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->orderByDesc('published_at');
    }

    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->published()
            ->orderBy('sort_order')
            ->orderByDesc('published_at');
    }

    public function categoryLabel(): string
    {
        if (! $this->category) {
            return 'UMKM';
        }

        return static::categories()[$this->category] ?? $this->category;
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

    public function formattedDate(): string
    {
        return $this->published_at?->translatedFormat('d F Y') ?? $this->created_at->translatedFormat('d F Y');
    }

    public function contactPhone(): ?string
    {
        return $this->whatsapp ?: $this->phone;
    }

    public function whatsappUrl(): ?string
    {
        $number = $this->whatsapp ?: $this->phone;

        if (! $number) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $number);

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return $digits ? 'https://wa.me/'.$digits : null;
    }

    public function relatedUmkms(int $limit = 4): Collection
    {
        $related = static::query()
            ->published()
            ->where('id', '!=', $this->id)
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();

        if ($related->count() < $limit) {
            $related = $related->concat(
                static::query()
                    ->published()
                    ->where('id', '!=', $this->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->orderByDesc('published_at')
                    ->limit($limit - $related->count())
                    ->get()
            );
        }

        return $related->take($limit);
    }

    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
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
