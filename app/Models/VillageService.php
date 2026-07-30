<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VillageService extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'icon',
        'excerpt',
        'description',
        'requirements',
        'procedures',
        'image_path',
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
            'surat' => 'Surat Administrasi',
            'permohonan' => 'Permohonan',
            'pengaduan' => 'Pengaduan & Aspirasi',
            'lainnya' => 'Lainnya',
        ];
    }

    public static function icons(): array
    {
        return [
            'ki-document' => 'Dokumen',
            'ki-notepad-edit' => 'Surat / Nota',
            'ki-clipboard' => 'Formulir',
            'ki-message-text-2' => 'Pesan / Pengaduan',
            'ki-verify' => 'Verifikasi',
            'ki-people' => 'Kependudukan',
            'ki-shop' => 'Usaha',
            'ki-home-2' => 'Domisili',
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
            return 'Layanan';
        }

        return static::categories()[$this->category] ?? $this->category;
    }

    public function iconClass(): string
    {
        return $this->icon ?: 'ki-document';
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

    public function relatedServices(int $limit = 4): Collection
    {
        $related = static::query()
            ->published()
            ->where('id', '!=', $this->id)
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();

        if ($related->count() < $limit) {
            $related = $related->concat(
                static::query()
                    ->published()
                    ->where('id', '!=', $this->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->orderBy('sort_order')
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
