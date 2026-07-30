<?php

use App\Models\VillageStatisticCategory;
use App\Models\VillageStatisticItem;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::demo1.base')] class extends Component {
    public ?int $selectedCategoryId = null;

    public bool $showCategoryForm = false;
    public ?int $editingCategoryId = null;
    public string $category_name = '';
    public string $category_icon = 'ki-chart-line';
    public string $category_description = '';
    public int $category_sort_order = 1;
    public bool $category_is_active = true;
    public bool $category_show_on_home = false;
    public array $category_columns = [];
    public string $category_chart_label_key = 'indikator';
    public string $category_chart_value_key = 'nilai';

    public ?int $editingItemId = null;
    public array $item_data = [];
    public int $item_sort_order = 1;
    public bool $item_is_active = true;

    public function mount(): void
    {
        $this->selectedCategoryId = VillageStatisticCategory::query()->orderBy('sort_order')->value('id');
    }

    public function with(): array
    {
        $categories = VillageStatisticCategory::query()->with('items')->orderBy('sort_order')->get();

        return [
            'categories' => $categories,
            'selectedCategory' => $categories->firstWhere('id', $this->selectedCategoryId),
            'iconOptions' => [
                'ki-chart-line' => 'Statistik',
                'ki-people' => 'Penduduk',
                'ki-home-2' => 'Keluarga',
                'ki-map' => 'Wilayah',
                'ki-geolocation' => 'Lokasi',
                'ki-book' => 'Pendidikan',
                'ki-heart' => 'Kesehatan',
                'ki-briefcase' => 'Ekonomi',
            ],
            'columnTypeOptions' => [
                'text' => 'Teks',
                'number' => 'Angka',
            ],
        ];
    }

    public function selectCategory(int $id): void
    {
        $this->selectedCategoryId = $id;
        $this->resetItemForm();
        $this->showCategoryForm = false;
    }

    public function openCategoryForm(?int $id = null): void
    {
        $this->resetItemForm();

        if ($id) {
            $category = VillageStatisticCategory::query()->findOrFail($id);
            $this->editingCategoryId = $category->id;
            $this->category_name = $category->name;
            $this->category_icon = $category->iconClass();
            $this->category_description = $category->description ?? '';
            $this->category_sort_order = $category->sort_order;
            $this->category_is_active = $category->is_active;
            $this->category_show_on_home = $category->show_on_home;
            $this->category_columns = $category->columnDefinitions();
            $this->category_chart_label_key = $category->chartLabelKey();
            $this->category_chart_value_key = $category->chartValueKey();
        } else {
            $this->resetCategoryForm();
        }

        $this->showCategoryForm = true;
    }

    public function addColumn(): void
    {
        $this->category_columns[] = [
            'key' => '',
            'label' => '',
            'type' => 'text',
            'required' => false,
        ];
    }

    public function removeColumn(int $index): void
    {
        if (! isset($this->category_columns[$index])) {
            return;
        }

        unset($this->category_columns[$index]);
        $this->category_columns = array_values($this->category_columns);
    }

    public function moveColumnUp(int $index): void
    {
        if ($index <= 0 || ! isset($this->category_columns[$index])) {
            return;
        }

        [$this->category_columns[$index - 1], $this->category_columns[$index]] =
            [$this->category_columns[$index], $this->category_columns[$index - 1]];
    }

    public function moveColumnDown(int $index): void
    {
        if ($index >= count($this->category_columns) - 1 || ! isset($this->category_columns[$index])) {
            return;
        }

        [$this->category_columns[$index + 1], $this->category_columns[$index]] =
            [$this->category_columns[$index], $this->category_columns[$index + 1]];
    }

    public function saveCategory(): void
    {
        $this->validate([
            'category_name' => 'required|string|max:100',
            'category_icon' => 'required|string|max:50',
            'category_description' => 'nullable|string|max:1000',
            'category_sort_order' => 'required|integer|min:0',
            'category_is_active' => 'boolean',
            'category_show_on_home' => 'boolean',
            'category_columns' => 'required|array|min:1',
            'category_columns.*.label' => 'required|string|max:100',
            'category_columns.*.key' => 'nullable|string|max:50',
            'category_columns.*.type' => 'required|in:text,number',
            'category_columns.*.required' => 'boolean',
            'category_chart_label_key' => 'required|string|max:50',
            'category_chart_value_key' => 'required|string|max:50',
        ]);

        $columns = VillageStatisticCategory::normalizeColumns($this->category_columns);

        if ($columns === []) {
            $this->addError('category_columns', 'Minimal satu kolom dengan label harus diisi.');

            return;
        }

        $columnKeys = array_column($columns, 'key');

        if (! in_array($this->category_chart_label_key, $columnKeys, true)) {
            $this->category_chart_label_key = $columnKeys[0];
        }

        if (! in_array($this->category_chart_value_key, $columnKeys, true)) {
            foreach ($columns as $column) {
                if ($column['type'] === 'number') {
                    $this->category_chart_value_key = $column['key'];
                    break;
                }
            }
        }

        $data = [
            'name' => $this->category_name,
            'slug' => VillageStatisticCategory::generateUniqueSlug($this->category_name, $this->editingCategoryId),
            'icon' => $this->category_icon,
            'description' => $this->category_description ?: null,
            'columns' => $columns,
            'chart_label_key' => $this->category_chart_label_key,
            'chart_value_key' => $this->category_chart_value_key,
            'sort_order' => $this->category_sort_order,
            'is_active' => $this->category_is_active,
            'show_on_home' => $this->category_show_on_home,
        ];

        if ($this->category_show_on_home) {
            VillageStatisticCategory::query()->update(['show_on_home' => false]);
        }

        if ($this->editingCategoryId) {
            $category = VillageStatisticCategory::query()->findOrFail($this->editingCategoryId);
            $category->update($data);
            $this->selectedCategoryId = $category->id;
            $message = 'Kategori statistik berhasil diperbarui.';
        } else {
            $category = VillageStatisticCategory::query()->create($data);
            $this->selectedCategoryId = $category->id;
            $message = 'Kategori statistik berhasil ditambahkan.';
        }

        VillageStatisticCategory::clearCache();
        $this->showCategoryForm = false;
        $this->resetCategoryForm();
        $this->dispatch('show-toast', message: $message, type: 'success');
    }

    public function deleteCategory(int $id): void
    {
        VillageStatisticCategory::query()->findOrFail($id)->delete();
        VillageStatisticCategory::clearCache();

        if ($this->selectedCategoryId === $id) {
            $this->selectedCategoryId = VillageStatisticCategory::query()->orderBy('sort_order')->value('id');
        }

        $this->showCategoryForm = false;
        $this->resetCategoryForm();
        $this->resetItemForm();
        $this->dispatch('show-toast', message: 'Kategori statistik berhasil dihapus.', type: 'success');
    }

    public function saveItem(): void
    {
        if (! $this->selectedCategoryId) {
            return;
        }

        $category = VillageStatisticCategory::query()->findOrFail($this->selectedCategoryId);
        $columns = $category->columnDefinitions();

        $rules = [
            'item_sort_order' => 'required|integer|min:0',
            'item_is_active' => 'boolean',
        ];

        foreach ($columns as $column) {
            $rule = ($column['required'] ?? false) ? 'required' : 'nullable';
            $rule .= '|string|max:500';
            $rules['item_data.'.$column['key']] = $rule;
        }

        $this->validate($rules);

        $data = [
            'village_statistic_category_id' => $this->selectedCategoryId,
            'data' => collect($columns)
                ->mapWithKeys(fn (array $column) => [
                    $column['key'] => trim((string) ($this->item_data[$column['key']] ?? '')),
                ])
                ->all(),
            'sort_order' => $this->item_sort_order,
            'is_active' => $this->item_is_active,
        ];

        if ($this->editingItemId) {
            VillageStatisticItem::query()->findOrFail($this->editingItemId)->update($data);
            $message = 'Data statistik berhasil diperbarui.';
        } else {
            VillageStatisticItem::query()->create($data);
            $message = 'Data statistik berhasil ditambahkan.';
        }

        VillageStatisticCategory::clearCache();
        $this->resetItemForm();
        $this->refreshStatisticChart();
        $this->dispatch('show-toast', message: $message, type: 'success');
    }

    public function editItem(int $id): void
    {
        $item = VillageStatisticItem::query()->with('category')->findOrFail($id);
        $columns = $item->category->columnDefinitions();

        $this->editingItemId = $item->id;
        $this->item_data = [];

        foreach ($columns as $column) {
            $this->item_data[$column['key']] = $item->valueFor($column['key']);
        }

        $this->item_sort_order = $item->sort_order;
        $this->item_is_active = $item->is_active;
    }

    public function deleteItem(int $id): void
    {
        VillageStatisticItem::query()->findOrFail($id)->delete();
        VillageStatisticCategory::clearCache();

        if ($this->editingItemId === $id) {
            $this->resetItemForm();
        }

        $this->refreshStatisticChart();
        $this->dispatch('show-toast', message: 'Data statistik berhasil dihapus.', type: 'success');
    }

    public function cancelCategoryForm(): void
    {
        $this->showCategoryForm = false;
        $this->resetCategoryForm();
    }

    public function cancelItemEdit(): void
    {
        $this->resetItemForm();
    }

    private function resetCategoryForm(): void
    {
        $this->editingCategoryId = null;
        $this->category_name = '';
        $this->category_icon = 'ki-chart-line';
        $this->category_description = '';
        $this->category_is_active = true;
        $this->category_show_on_home = false;
        $this->category_columns = VillageStatisticCategory::defaultColumns();
        $this->category_chart_label_key = 'indikator';
        $this->category_chart_value_key = 'nilai';
        $this->category_sort_order = (int) VillageStatisticCategory::query()->max('sort_order') + 1;
        $this->resetValidation([
            'category_name', 'category_icon', 'category_description',
            'category_sort_order', 'category_is_active', 'category_show_on_home',
            'category_columns', 'category_chart_label_key', 'category_chart_value_key',
        ]);
    }

    private function resetItemForm(): void
    {
        $this->editingItemId = null;
        $this->item_data = [];
        $this->item_is_active = true;
        $this->item_sort_order = $this->selectedCategoryId
            ? (int) VillageStatisticItem::query()->where('village_statistic_category_id', $this->selectedCategoryId)->max('sort_order') + 1
            : 1;

        if ($this->selectedCategoryId) {
            $category = VillageStatisticCategory::query()->find($this->selectedCategoryId);

            if ($category) {
                foreach ($category->columnDefinitions() as $column) {
                    $this->item_data[$column['key']] = '';
                }
            }
        }

        $this->resetValidation(['item_data', 'item_sort_order', 'item_is_active']);
    }

    public function refreshStatisticChart(): void
    {
        if (! $this->selectedCategoryId || $this->showCategoryForm) {
            return;
        }

        $category = VillageStatisticCategory::query()->find($this->selectedCategoryId);

        if (! $category?->chartHasData()) {
            return;
        }

        $this->dispatch(
            'statistic-chart-refresh',
            chartId: 'admin-statistic-chart-'.$category->id,
            config: $category->chartDataset(),
        );
    }
};
?>

<div>
    <div class="kt-container-fixed">
        <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5 lg:items-end">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-mono">Statistika Desa</h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    Kelola kategori, kolom tabel, dan data statistik desa
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('statistika.index') }}" target="_blank" class="kt-btn kt-btn-outline">
                    <i class="ki-filled ki-eye"></i>
                    Lihat di Website
                </a>
                <button type="button" wire:click="openCategoryForm" class="kt-btn kt-btn-primary">
                    <i class="ki-filled ki-plus"></i>
                    Kategori Baru
                </button>
            </div>
        </div>
    </div>

    <div class="kt-container-fixed">
        <div class="overflow-hidden rounded-xl border border-border bg-background shadow-sm">
            <div class="grid lg:grid-cols-[280px_1fr]">
                <aside class="border-b border-border bg-accent/20 lg:border-b-0 lg:border-r">
                    <div class="border-b border-border px-5 py-4">
                        <h2 class="text-xs font-bold uppercase tracking-wide text-muted-foreground">Kategori Statistik</h2>
                    </div>
                    <nav class="p-3">
                        @forelse ($categories as $category)
                            <button type="button" wire:click="selectCategory({{ $category->id }})"
                                @class([
                                    'mb-1 flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm font-medium transition',
                                    'bg-primary text-primary-foreground shadow-sm' => $selectedCategoryId === $category->id,
                                    'text-foreground hover:bg-accent/60' => $selectedCategoryId !== $category->id,
                                ])>
                                <i class="ki-filled {{ $category->iconClass() }} text-base"></i>
                                <span class="min-w-0 flex-1 truncate">{{ $category->name }}</span>
                                @unless ($category->is_active)
                                    <span class="kt-badge kt-badge-xs kt-badge-secondary">Off</span>
                                @endunless
                            </button>
                        @empty
                            <p class="px-3 py-4 text-sm text-muted-foreground">Belum ada kategori.</p>
                        @endforelse
                    </nav>
                </aside>

                <div class="p-5 lg:p-7">
                    @if ($showCategoryForm)
                        <div class="kt-card mb-5">
                            <div class="kt-card-header">
                                <h3 class="kt-card-title">{{ $editingCategoryId ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>
                            </div>
                            <div class="kt-card-content">
                                <form wire:submit.prevent="saveCategory" class="grid gap-4 lg:grid-cols-2">
                                    <div class="kt-form-item lg:col-span-2">
                                        <label class="kt-form-label">Nama Kategori</label>
                                        <input type="text" class="kt-input w-full" wire:model="category_name" placeholder="Penduduk" />
                                        @error('category_name') <div class="kt-form-message">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="kt-form-item lg:col-span-2">
                                        <label class="kt-form-label">Deskripsi</label>
                                        <textarea class="kt-input w-full min-h-[80px]" wire:model="category_description" rows="2"></textarea>
                                        @error('category_description') <div class="kt-form-message">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="kt-form-item">
                                        <label class="kt-form-label">Ikon</label>
                                        <select class="kt-input w-full" wire:model="category_icon">
                                            @foreach ($iconOptions as $iconValue => $iconLabel)
                                                <option value="{{ $iconValue }}">{{ $iconLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="kt-form-item">
                                        <label class="kt-form-label">Urutan</label>
                                        <input type="number" min="0" class="kt-input w-full" wire:model="category_sort_order" />
                                    </div>
                                    <div class="kt-form-item lg:col-span-2 flex flex-wrap gap-4">
                                        <label class="kt-label">
                                            <input class="kt-checkbox kt-checkbox-sm" type="checkbox" wire:model="category_is_active" />
                                            <span class="kt-checkbox-label">Aktif</span>
                                        </label>
                                        <label class="kt-label">
                                            <input class="kt-checkbox kt-checkbox-sm" type="checkbox" wire:model="category_show_on_home" />
                                            <span class="kt-checkbox-label">Tampilkan ringkasan di beranda</span>
                                        </label>
                                    </div>

                                    <div class="kt-form-item lg:col-span-2">
                                        <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                                            <label class="kt-form-label mb-0">Kolom Tabel</label>
                                            <button type="button" wire:click="addColumn" class="kt-btn kt-btn-sm kt-btn-outline">
                                                <i class="ki-filled ki-plus"></i>
                                                Tambah Kolom
                                            </button>
                                        </div>
                                        @error('category_columns') <div class="kt-form-message mb-2">{{ $message }}</div> @enderror
                                        <div class="space-y-3 rounded-lg border border-border p-3">
                                            @forelse ($category_columns as $index => $column)
                                                <div class="grid gap-3 rounded-lg bg-accent/20 p-3 lg:grid-cols-[1fr_140px_120px_auto_auto]" wire:key="column-{{ $index }}">
                                                    <div>
                                                        <label class="mb-1 block text-xs text-muted-foreground">Label Kolom</label>
                                                        <input type="text" class="kt-input w-full" wire:model="category_columns.{{ $index }}.label" placeholder="Indikator" />
                                                        @error('category_columns.'.$index.'.label') <div class="kt-form-message">{{ $message }}</div> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="mb-1 block text-xs text-muted-foreground">Kunci (opsional)</label>
                                                        <input type="text" class="kt-input w-full" wire:model="category_columns.{{ $index }}.key" placeholder="indikator" />
                                                    </div>
                                                    <div>
                                                        <label class="mb-1 block text-xs text-muted-foreground">Tipe</label>
                                                        <select class="kt-input w-full" wire:model="category_columns.{{ $index }}.type">
                                                            @foreach ($columnTypeOptions as $typeValue => $typeLabel)
                                                                <option value="{{ $typeValue }}">{{ $typeLabel }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="flex items-end">
                                                        <label class="kt-label">
                                                            <input class="kt-checkbox kt-checkbox-sm" type="checkbox" wire:model="category_columns.{{ $index }}.required" />
                                                            <span class="kt-checkbox-label text-xs">Wajib</span>
                                                        </label>
                                                    </div>
                                                    <div class="flex items-end gap-1">
                                                        <button type="button" wire:click="moveColumnUp({{ $index }})" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" title="Naik">
                                                            <i class="ki-filled ki-up"></i>
                                                        </button>
                                                        <button type="button" wire:click="moveColumnDown({{ $index }})" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" title="Turun">
                                                            <i class="ki-filled ki-down"></i>
                                                        </button>
                                                        <button type="button" wire:click="removeColumn({{ $index }})" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost text-red-500" title="Hapus">
                                                            <i class="ki-filled ki-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-sm text-muted-foreground">Belum ada kolom. Klik "Tambah Kolom" untuk memulai.</p>
                                            @endforelse
                                        </div>
                                    </div>

                                    @php
                                        $previewKeys = collect($category_columns)
                                            ->filter(fn ($col) => filled($col['label'] ?? ''))
                                            ->map(fn ($col) => filled($col['key'] ?? '') ? $col['key'] : \Illuminate\Support\Str::slug($col['label'], '_'))
                                            ->values();
                                    @endphp
                                    @if ($previewKeys->isNotEmpty())
                                        <div class="kt-form-item">
                                            <label class="kt-form-label">Kolom Label Grafik</label>
                                            <select class="kt-input w-full" wire:model="category_chart_label_key">
                                                @foreach ($category_columns as $column)
                                                    @if (filled($column['label'] ?? ''))
                                                        @php $key = filled($column['key'] ?? '') ? $column['key'] : \Illuminate\Support\Str::slug($column['label'], '_'); @endphp
                                                        <option value="{{ $key }}">{{ $column['label'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="kt-form-item">
                                            <label class="kt-form-label">Kolom Nilai Grafik</label>
                                            <select class="kt-input w-full" wire:model="category_chart_value_key">
                                                @foreach ($category_columns as $column)
                                                    @if (filled($column['label'] ?? '') && ($column['type'] ?? 'text') === 'number')
                                                        @php $key = filled($column['key'] ?? '') ? $column['key'] : \Illuminate\Support\Str::slug($column['label'], '_'); @endphp
                                                        <option value="{{ $key }}">{{ $column['label'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @php
                                                $numberColumnCount = collect($category_columns)->where('type', 'number')->count();
                                            @endphp
                                            @if ($numberColumnCount >= 2)
                                                <p class="mt-1 text-xs text-muted-foreground">
                                                    Kategori ini memiliki {{ $numberColumnCount }} kolom angka. Grafik akan membandingkan semua kolom angka secara berdampingan.
                                                </p>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="flex gap-2 lg:col-span-2">
                                        <button type="submit" class="kt-btn kt-btn-primary">Simpan Kategori</button>
                                        <button type="button" wire:click="cancelCategoryForm" class="kt-btn kt-btn-outline">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @elseif ($selectedCategory)
                        <div class="mb-5 flex flex-wrap items-start justify-between gap-3 border-b border-border pb-5">
                            <div>
                                <h2 class="text-lg font-semibold text-foreground">{{ $selectedCategory->name }}</h2>
                                @if ($selectedCategory->description)
                                    <p class="mt-1 text-sm text-muted-foreground">{{ $selectedCategory->description }}</p>
                                @endif
                                <p class="mt-2 text-xs text-muted-foreground">
                                    {{ count($selectedCategory->columnDefinitions()) }} kolom ·
                                    Grafik: {{ collect($selectedCategory->columnDefinitions())->firstWhere('key', $selectedCategory->chartLabelKey())['label'] ?? 'Label' }}
                                    / {{ collect($selectedCategory->columnDefinitions())->firstWhere('key', $selectedCategory->chartValueKey())['label'] ?? 'Nilai' }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" wire:click="openCategoryForm({{ $selectedCategory->id }})" class="kt-btn kt-btn-sm kt-btn-outline">
                                    Edit Kategori
                                </button>
                                <button type="button" wire:click="deleteCategory({{ $selectedCategory->id }})"
                                    wire:confirm="Hapus kategori ini beserta seluruh datanya?"
                                    class="kt-btn kt-btn-sm kt-btn-outline text-red-500">
                                    Hapus
                                </button>
                            </div>
                        </div>

                        <div class="kt-card mb-5">
                            <div class="kt-card-header">
                                <h3 class="kt-card-title">{{ $editingItemId ? 'Edit Data' : 'Tambah Data Statistik' }}</h3>
                            </div>
                            <div class="kt-card-content">
                                <form wire:submit.prevent="saveItem" class="grid gap-4 lg:grid-cols-2">
                                    @foreach ($selectedCategory->columnDefinitions() as $column)
                                        <div @class(['kt-form-item', 'lg:col-span-2' => ($column['type'] ?? 'text') === 'text' && count($selectedCategory->columnDefinitions()) <= 3])>
                                            <label class="kt-form-label">
                                                {{ $column['label'] }}
                                                @if ($column['required'] ?? false)
                                                    <span class="text-red-500">*</span>
                                                @endif
                                            </label>
                                            <input
                                                type="{{ ($column['type'] ?? 'text') === 'number' ? 'text' : 'text' }}"
                                                class="kt-input w-full"
                                                wire:model="item_data.{{ $column['key'] }}"
                                                placeholder="{{ $column['label'] }}"
                                            />
                                            @error('item_data.'.$column['key']) <div class="kt-form-message">{{ $message }}</div> @enderror
                                        </div>
                                    @endforeach
                                    <div class="kt-form-item">
                                        <label class="kt-form-label">Urutan</label>
                                        <input type="number" min="0" class="kt-input w-full" wire:model="item_sort_order" />
                                    </div>
                                    <div class="kt-form-item flex items-end">
                                        <label class="kt-label">
                                            <input class="kt-checkbox kt-checkbox-sm" type="checkbox" wire:model="item_is_active" />
                                            <span class="kt-checkbox-label">Aktif</span>
                                        </label>
                                    </div>
                                    <div class="flex gap-2 lg:col-span-2">
                                        <button type="submit" class="kt-btn kt-btn-primary">
                                            {{ $editingItemId ? 'Perbarui Data' : 'Tambah Data' }}
                                        </button>
                                        @if ($editingItemId)
                                            <button type="button" wire:click="cancelItemEdit" class="kt-btn kt-btn-outline">Batal</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>

                        @php $columns = $selectedCategory->columnDefinitions(); @endphp
                        <div class="kt-card">
                            <div class="kt-card-table">
                                <div class="kt-table-wrapper overflow-auto">
                                    <table class="kt-table kt-table-border min-w-[640px]">
                                        <thead>
                                            <tr>
                                                @foreach ($columns as $column)
                                                    <th>{{ $column['label'] }}</th>
                                                @endforeach
                                                <th>Status</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($selectedCategory->items as $item)
                                                <tr wire:key="item-{{ $item->id }}">
                                                    @foreach ($columns as $column)
                                                        <td @class(['font-medium text-foreground' => $loop->first, 'text-muted-foreground' => ! $loop->first])>
                                                            {{ $item->valueFor($column['key']) ?: '-' }}
                                                        </td>
                                                    @endforeach
                                                    <td>
                                                        <span class="kt-badge kt-badge-sm {{ $item->is_active ? 'kt-badge-success' : 'kt-badge-secondary' }} kt-badge-outline">
                                                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="flex justify-end gap-1">
                                                            <button type="button" wire:click="editItem({{ $item->id }})" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" title="Edit">
                                                                <i class="ki-filled ki-notepad-edit"></i>
                                                            </button>
                                                            <button type="button" wire:click="deleteItem({{ $item->id }})"
                                                                wire:confirm="Hapus data statistik ini?"
                                                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost text-red-500" title="Hapus">
                                                                <i class="ki-filled ki-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ count($columns) + 2 }}" class="py-10 text-center text-muted-foreground">
                                                        Belum ada data statistik untuk kategori ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @if ($selectedCategory->chartHasData())
                            <div
                                class="mt-5"
                                wire:key="statistic-chart-{{ $selectedCategoryId }}"
                                wire:init="refreshStatisticChart"
                            >
                                <x-statistic-chart
                                    :chart-id="'admin-statistic-chart-'.$selectedCategoryId"
                                    title="Grafik Statistik"
                                    :dataset="$selectedCategory->chartDataset()"
                                    :auto-init="false"
                                    class="border border-border shadow-none"
                                />
                            </div>

                            <style>
                                .statistic-chart-type-btn { color: var(--muted-foreground, #6b7280); }
                                .statistic-chart-type-btn.statistic-chart-type-active {
                                    background-color: var(--primary, #2D5A27);
                                    color: var(--primary-foreground, #fff);
                                }
                            </style>
                        @endif
                    @else
                        <div class="py-20 text-center text-muted-foreground">
                            Tambahkan kategori statistik untuk mulai mengelola data.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('statistic-chart-refresh', ({ chartId, config }) => {
            if (typeof window.initStatisticChartTypeSelector !== 'function') {
                return;
            }

            window.initStatisticChartTypeSelector(chartId, config, 320, 'bar');
        });
    </script>
    @endscript
</div>
