<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VillageDocument extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'file_path',
        'file_size',
        'is_published',
        'published_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
            'file_size' => 'integer',
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
        return $query->published()
            ->orderBy('sort_order')
            ->orderByDesc('published_at');
    }

    public function fileUrl(): string
    {
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }

        if (str_starts_with($this->file_path, 'assets/')) {
            return asset($this->file_path);
        }

        return '/storage/'.ltrim($this->file_path, '/');
    }

    public function fileExtension(): string
    {
        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }

    public function fileTypeLabel(): string
    {
        return match ($this->fileExtension()) {
            'pdf' => 'PDF',
            'jpg', 'jpeg', 'png', 'webp' => 'Gambar',
            default => strtoupper($this->fileExtension()),
        };
    }

    public function formattedSize(): string
    {
        $bytes = $this->file_size;

        if (! $bytes) {
            return '-';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1, ',', '.').' MB';
        }

        return number_format($bytes / 1024, 0, ',', '.').' KB';
    }

    public function formattedDate(): string
    {
        return $this->published_at?->translatedFormat('d F Y') ?? $this->created_at->translatedFormat('d F Y');
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
