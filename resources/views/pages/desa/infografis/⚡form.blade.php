<?php

use App\Models\VillageInfographic;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts::demo1.base')] class extends Component {
    use WithFileUploads;

    public ?VillageInfographic $infographic = null;

    public string $title = '';
    public string $description = '';
    public bool $is_published = true;
    public int $sort_order = 1;
    public ?string $current_image_path = null;

    public $image;

    public function mount(?VillageInfographic $infographic = null): void
    {
        $this->infographic = $infographic;

        if ($infographic?->exists) {
            $this->fill($infographic->only(['title', 'is_published', 'sort_order']));
            $this->description = $infographic->description ?? '';
            $this->current_image_path = $infographic->image_path;
        } else {
            $this->sort_order = (int) VillageInfographic::query()->max('sort_order') + 1;
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'is_published' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'image' => ($this->infographic?->exists ? 'nullable' : 'required').'|image|max:10240',
        ], [
            'image.required' => 'Gambar infografis wajib diunggah.',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => VillageInfographic::generateUniqueSlug($this->title, $this->infographic?->id),
            'description' => $this->description ?: null,
            'is_published' => $this->is_published,
            'sort_order' => $this->sort_order,
            'published_at' => $this->is_published
                ? ($this->infographic?->published_at ?? now())
                : null,
        ];

        if ($this->image) {
            if ($this->infographic?->image_path && ! str_starts_with($this->infographic->image_path, 'assets/')) {
                Storage::disk('public')->delete($this->infographic->image_path);
            }
            $data['image_path'] = $this->image->store('desa/infografis', 'public');
            $this->current_image_path = $data['image_path'];
            $this->image = null;
        }

        if ($this->infographic?->exists) {
            $this->infographic->update($data);
            $message = 'Infografis berhasil diperbarui.';
        } else {
            $this->infographic = VillageInfographic::query()->create($data);
            $message = 'Infografis berhasil ditambahkan.';
        }

        $this->js('sessionStorage.setItem("ktPendingToast", '.json_encode(['message' => $message, 'type' => 'success']).')');
        $this->redirect(route('desa.infografis.index'), navigate: true);
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
                    {{ $infographic?->exists ? 'Edit Infografis' : 'Tambah Infografis' }}
                </h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    {{ $infographic?->exists ? 'Perbarui infografis desa' : 'Unggah infografis baru untuk website desa' }}
                </p>
            </div>
            <a href="{{ route('desa.infografis.index') }}" class="kt-btn kt-btn-outline" wire:navigate>
                <i class="ki-filled ki-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="kt-container-fixed">
        <form wire:submit.prevent="save" class="grid gap-5 lg:gap-7.5">
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Informasi Infografis</h3>
                </div>
                <div class="kt-card-content grid gap-5">
                    <div class="kt-form-item">
                        <label class="kt-form-label">Judul</label>
                        <input type="text" class="kt-input w-full" wire:model="title"
                            placeholder="Contoh: Data Kependudukan Desa 2024" />
                        @error('title') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Deskripsi Singkat</label>
                        <textarea class="kt-textarea" wire:model="description" rows="3"
                            placeholder="Keterangan singkat tentang isi infografis (opsional)"></textarea>
                        @error('description') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Gambar & Publikasi</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Gambar Infografis</label>
                        <div class="grid gap-3">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                    class="max-h-96 w-full max-w-md rounded-lg object-contain bg-gray-50" />
                            @elseif ($this->previewUrl($current_image_path))
                                <img src="{{ $this->previewUrl($current_image_path) }}" alt="Infografis"
                                    class="max-h-96 w-full max-w-md rounded-lg object-contain bg-gray-50" />
                            @endif
                            <input type="file" wire:model="image" accept="image/*" class="text-sm" />
                            <p class="text-xs text-muted-foreground">PNG/JPG, maks. 10MB. Disarankan format potret atau persegi agar mudah dibaca.</p>
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
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('desa.infografis.index') }}" class="kt-btn kt-btn-outline" wire:navigate>Batal</a>
                <button type="submit" class="kt-btn kt-btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
