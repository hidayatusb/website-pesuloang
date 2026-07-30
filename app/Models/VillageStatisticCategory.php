<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VillageStatisticCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'columns',
        'chart_label_key',
        'chart_value_key',
        'sort_order',
        'is_active',
        'show_on_home',
    ];

    protected function casts(): array
    {
        return [
            'columns' => 'array',
            'is_active' => 'boolean',
            'show_on_home' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(VillageStatisticItem::class)->orderBy('sort_order');
    }

    public function activeItems(): HasMany
    {
        return $this->items()->where('is_active', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public static function defaultColumns(): array
    {
        return [
            ['key' => 'indikator', 'label' => 'Indikator', 'type' => 'text', 'required' => true],
            ['key' => 'nilai', 'label' => 'Nilai', 'type' => 'number', 'required' => true],
            ['key' => 'satuan', 'label' => 'Satuan', 'type' => 'text', 'required' => false],
            ['key' => 'periode', 'label' => 'Periode', 'type' => 'text', 'required' => false],
        ];
    }

    public function columnDefinitions(): array
    {
        $columns = $this->columns;

        if (! is_array($columns) || $columns === []) {
            return static::defaultColumns();
        }

        return $columns;
    }

    public function columnKeys(): array
    {
        return array_column($this->columnDefinitions(), 'key');
    }

    public function chartLabelKey(): string
    {
        $key = $this->chart_label_key;

        return in_array($key, $this->columnKeys(), true)
            ? $key
            : ($this->columnKeys()[0] ?? 'indikator');
    }

    public function chartValueKey(): string
    {
        $key = $this->chart_value_key;

        if (in_array($key, $this->columnKeys(), true)) {
            return $key;
        }

        foreach ($this->columnDefinitions() as $column) {
            if (($column['type'] ?? 'text') === 'number') {
                return $column['key'];
            }
        }

        return $this->columnKeys()[1] ?? 'nilai';
    }

    public function numericColumns(): array
    {
        return array_values(array_filter(
            $this->columnDefinitions(),
            fn (array $column) => ($column['type'] ?? 'text') === 'number',
        ));
    }

    public function unitColumnKey(): ?string
    {
        foreach ($this->columnDefinitions() as $column) {
            if (
                $column['key'] === 'satuan'
                && ($column['type'] ?? 'text') === 'text'
                && $column['key'] !== $this->chartLabelKey()
                && $column['key'] !== $this->chartValueKey()
            ) {
                return 'satuan';
            }
        }

        return null;
    }

    public static function normalizeColumns(array $columns): array
    {
        $normalized = [];
        $usedKeys = [];

        foreach ($columns as $index => $column) {
            $label = trim((string) ($column['label'] ?? ''));
            $key = trim((string) ($column['key'] ?? ''));

            if ($label === '') {
                continue;
            }

            if ($key === '') {
                $key = Str::slug($label, '_');
            }

            $key = Str::slug($key, '_');

            if ($key === '') {
                $key = 'kolom_'.$index;
            }

            while (in_array($key, $usedKeys, true)) {
                $key .= '_'.($index + 1);
            }

            $usedKeys[] = $key;

            $normalized[] = [
                'key' => $key,
                'label' => $label,
                'type' => in_array($column['type'] ?? 'text', ['text', 'number'], true) ? $column['type'] : 'text',
                'required' => (bool) ($column['required'] ?? false),
            ];
        }

        return $normalized;
    }

    public static function forDisplay(): Collection
    {
        return Cache::remember('village_statistic_categories', 3600, function () {
            return static::query()
                ->active()
                ->with(['activeItems'])
                ->get();
        });
    }

    public static function homeHighlights(): Collection
    {
        return Cache::remember('village_statistic_home', 3600, function () {
            $category = static::query()
                ->active()
                ->where('show_on_home', true)
                ->with(['activeItems'])
                ->orderBy('sort_order')
                ->first();

            return $category?->activeItems ?? collect();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('village_statistic_categories');
        Cache::forget('village_statistic_home');
    }

    public function chartDataset(): array
    {
        $labelKey = $this->chartLabelKey();
        $numericColumns = $this->numericColumns();

        if (count($numericColumns) >= 2) {
            $items = $this->activeItems
                ->filter(fn (VillageStatisticItem $item) => $item->valueFor($labelKey) !== '')
                ->values();

            return [
                'mode' => 'grouped',
                'categories' => $items
                    ->map(fn (VillageStatisticItem $item) => $item->valueFor($labelKey))
                    ->all(),
                'series' => array_map(
                    fn (array $column) => [
                        'name' => $column['label'],
                        'data' => $items
                            ->map(fn (VillageStatisticItem $item) => $item->chartValueFor($column['key']) ?? 0)
                            ->all(),
                    ],
                    $numericColumns,
                ),
            ];
        }

        $valueKey = $this->chartValueKey();
        $unitKey = $this->unitColumnKey();

        return [
            'mode' => 'simple',
            'rows' => $this->activeItems
                ->map(fn (VillageStatisticItem $item) => [
                    'label' => $item->valueFor($labelKey),
                    'value' => $item->chartValueFor($valueKey),
                    'unit' => $unitKey ? $item->valueFor($unitKey) : null,
                ])
                ->filter(fn (array $row) => $row['label'] !== '' && $row['value'] !== null)
                ->values()
                ->all(),
        ];
    }

    public function chartHasData(): bool
    {
        $dataset = $this->chartDataset();

        if (($dataset['mode'] ?? 'simple') === 'grouped') {
            return count($dataset['categories'] ?? []) > 0
                && count($dataset['series'] ?? []) > 0;
        }

        return count($dataset['rows'] ?? []) >= 2;
    }

    public function iconClass(): string
    {
        $icon = trim($this->icon);

        if ($icon === '') {
            return 'ki-chart';
        }

        if (str_starts_with($icon, 'ki-')) {
            return $icon;
        }

        return 'ki-'.$icon;
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
