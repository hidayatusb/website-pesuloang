<?php

use App\Models\VillageService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts::demo1.base')] class extends Component {
    use WithFileUploads;

    public ?VillageService $service = null;

    public string $title = '';
    public string $category = 'surat';
    public string $icon = 'ki-document';
    public string $excerpt = '';
    public string $description = '';
    public string $requirements = '';
    public string $procedures = '';
    public bool $is_published = true;
    public bool $is_featured = false;
    public int $sort_order = 1;
    public ?string $current_image_path = null;

    public $image;

    public function mount(?VillageService $service = null): void
    {
        $this->service = $service;

        if ($service?->exists) {
            $this->fill($service->only([
                'title', 'category', 'icon', 'excerpt', 'description',
                'requirements', 'procedures',
                'is_published', 'is_featured', 'sort_order',
            ]));
            $this->current_image_path = $service->image_path;
        } else {
            $this->sort_order = (int) VillageService::query()->max('sort_order') + 1;
            $this->requirements = '<ul><li></li></ul>';
            $this->procedures = '<ol><li></li></ol>';
        }
    }

    public function with(): array
    {
        return [
            'categories' => VillageService::categories(),
            'icons' => VillageService::icons(),
        ];
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:150',
            'category' => 'required|in:'.implode(',', array_keys(VillageService::categories())),
            'icon' => 'required|in:'.implode(',', array_keys(VillageService::icons())),
            'excerpt' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'requirements' => 'required|string',
            'procedures' => 'required|string',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => VillageService::generateUniqueSlug($this->title, $this->service?->id),
            'category' => $this->category,
            'icon' => $this->icon,
            'excerpt' => $this->excerpt ?: null,
            'description' => $this->description ?: null,
            'requirements' => $this->requirements,
            'procedures' => $this->procedures,
            'is_published' => $this->is_published,
            'is_featured' => $this->is_featured,
            'sort_order' => $this->sort_order,
            'published_at' => $this->is_published
                ? ($this->service?->published_at ?? now())
                : null,
        ];

        if ($this->image) {
            if ($this->service?->image_path && ! str_starts_with($this->service->image_path, 'assets/')) {
                Storage::disk('public')->delete($this->service->image_path);
            }
            $data['image_path'] = $this->image->store('desa/layanan', 'public');
            $this->current_image_path = $data['image_path'];
            $this->image = null;
        }

        if ($this->service?->exists) {
            $this->service->update($data);
            $message = 'Layanan berhasil diperbarui.';
        } else {
            $this->service = VillageService::query()->create($data);
            $message = 'Layanan berhasil ditambahkan.';
        }

        $this->js('sessionStorage.setItem("ktPendingToast", '.json_encode(['message' => $message, 'type' => 'success']).')');
        $this->redirect(route('desa.layanan.index'), navigate: true);
    }

    public function previewUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return '/storage/'.ltrim($path, '/');
    }
};
?>

<div>
    <div class="kt-container-fixed">
        <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5 lg:items-end">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-mono">
                    {{ $service?->exists ? 'Edit Layanan' : 'Tambah Layanan' }}
                </h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    {{ $service?->exists ? 'Perbarui informasi layanan desa' : 'Tambahkan layanan desa baru' }}
                </p>
            </div>
            <a href="{{ route('desa.layanan.index') }}" class="kt-btn kt-btn-outline" wire:navigate>
                <i class="ki-filled ki-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="kt-container-fixed">
        <form wire:submit.prevent="save" class="grid gap-5 lg:gap-7.5">
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Informasi Layanan</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Nama Layanan</label>
                        <input type="text" class="kt-input w-full" wire:model="title" placeholder="Surat Keterangan Domisili" />
                        @error('title') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Kategori</label>
                        <select class="kt-input w-full" wire:model="category">
                            @foreach ($categories as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Ikon</label>
                        <select class="kt-input w-full" wire:model="icon">
                            @foreach ($icons as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('icon') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Ringkasan</label>
                        <textarea class="kt-textarea" wire:model="excerpt" rows="2"
                            placeholder="Deskripsi singkat untuk kartu layanan"></textarea>
                        @error('excerpt') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <x-rich-editor
                            model="description"
                            :value="$description"
                            label="Deskripsi Layanan"
                            placeholder="Penjelasan umum tentang layanan"
                            min-height="260px"
                        />
                        @error('description') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Persyaratan</h3>
                </div>
                <div class="kt-card-content">
                    <div>
                        <x-rich-editor
                            model="requirements"
                            :value="$requirements"
                            label="Daftar Persyaratan"
                            placeholder="Tuliskan daftar persyaratan layanan"
                            min-height="280px"
                        />
                        <p class="mt-2 text-xs text-muted-foreground">Gunakan tombol daftar bullet atau nomor di toolbar editor.</p>
                        @error('requirements') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Prosedur</h3>
                </div>
                <div class="kt-card-content">
                    <div>
                        <x-rich-editor
                            model="procedures"
                            :value="$procedures"
                            label="Alur / Tahapan Prosedur"
                            placeholder="Tuliskan langkah-langkah prosedur layanan"
                            min-height="280px"
                        />
                        <p class="mt-2 text-xs text-muted-foreground">Gunakan daftar bernomor untuk langkah-langkah berurutan.</p>
                        @error('procedures') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Gambar & Publikasi</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Gambar Ilustrasi (opsional)</label>
                        <div class="grid gap-3">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="max-h-48 w-full max-w-md rounded-lg object-cover" />
                            @elseif ($this->previewUrl($current_image_path))
                                <img src="{{ $this->previewUrl($current_image_path) }}" alt="Gambar layanan" class="max-h-48 w-full max-w-md rounded-lg object-cover" />
                            @endif
                            <input type="file" wire:model="image" accept="image/*" class="text-sm" />
                            <p class="text-xs text-muted-foreground">PNG/JPG, maks. 5MB.</p>
                        </div>
                        @error('image') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Urutan</label>
                        <input type="number" min="0" class="kt-input w-full" wire:model="sort_order" />
                        @error('sort_order') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item flex flex-col justify-end gap-3">
                        <label class="kt-label">
                            <input class="kt-checkbox kt-checkbox-sm" type="checkbox" wire:model="is_published" />
                            <span class="kt-checkbox-label">Publikasikan ke website</span>
                        </label>
                        <label class="kt-label">
                            <input class="kt-checkbox kt-checkbox-sm" type="checkbox" wire:model="is_featured" />
                            <span class="kt-checkbox-label">Tampilkan sebagai layanan unggulan di beranda</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('desa.layanan.index') }}" class="kt-btn kt-btn-outline" wire:navigate>Batal</a>
                <button type="submit" class="kt-btn kt-btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>

</div>
