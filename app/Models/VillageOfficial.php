<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VillageOfficial extends Model
{
    protected $fillable = [
        'name',
        'position',
        'photo_path',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function photoUrl(): string
    {
        if (! $this->photo_path) {
            return asset('assets/media/avatars/300-1.png');
        }

        if (str_starts_with($this->photo_path, 'http://') || str_starts_with($this->photo_path, 'https://')) {
            return $this->photo_path;
        }

        if (str_starts_with($this->photo_path, 'assets/')) {
            return asset($this->photo_path);
        }

        return '/storage/'.ltrim($this->photo_path, '/');
    }
}
