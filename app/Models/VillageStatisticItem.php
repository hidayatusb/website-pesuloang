<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillageStatisticItem extends Model
{
    protected $fillable = [
        'village_statistic_category_id',
        'data',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VillageStatisticCategory::class, 'village_statistic_category_id');
    }

    public function valueFor(string $key): string
    {
        $value = $this->data[$key] ?? '';

        return is_scalar($value) ? trim((string) $value) : '';
    }

    public function displayValue(): string
    {
        $category = $this->relationLoaded('category') ? $this->category : $this->category()->first();

        if (! $category) {
            return '';
        }

        $value = $this->valueFor($category->chartValueKey());
        $unitKey = $category->unitColumnKey();
        $unit = $unitKey ? $this->valueFor($unitKey) : '';

        return trim($value.($unit !== '' ? ' '.$unit : ''));
    }

    public function homeLabel(): string
    {
        $category = $this->relationLoaded('category') ? $this->category : $this->category()->first();

        return $category ? $this->valueFor($category->chartLabelKey()) : '';
    }

    public function chartValueFor(string $key): ?float
    {
        return static::parseNumeric($this->valueFor($key));
    }

    public function chartValue(): ?float
    {
        $category = $this->relationLoaded('category') ? $this->category : $this->category()->first();

        return $category ? $this->chartValueFor($category->chartValueKey()) : null;
    }

    public static function parseNumeric(string $raw): ?float
    {
        $raw = trim($raw);

        if ($raw === '') {
            return null;
        }

        $numeric = preg_replace('/[^\d,.]/', '', $raw);

        if ($numeric === '' || $numeric === null) {
            return null;
        }

        if (str_contains($numeric, ',') && str_contains($numeric, '.')) {
            $numeric = str_replace('.', '', $numeric);
            $numeric = str_replace(',', '.', $numeric);
        } elseif (str_contains($numeric, ',')) {
            $numeric = str_replace(',', '.', $numeric);
        } elseif (preg_match('/^\d{1,3}(\.\d{3})+$/', $numeric)) {
            $numeric = str_replace('.', '', $numeric);
        }

        return is_numeric($numeric) ? (float) $numeric : null;
    }
}
