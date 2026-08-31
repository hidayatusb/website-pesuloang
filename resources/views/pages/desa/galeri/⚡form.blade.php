<?php

use App\Models\VillageGallery;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts::demo1.base')] class extends Component {
    use WithFileUploads;

    public ?VillageGallery $gallery = null;

    public string $title = '';
    public bool $is_published = true;
    public int $sort_order = 1;
    public ?string $current_image_path = null;

    public $image;

    public function mount(?VillageGallery $gallery = null): void
    {
        $this->gallery = $gallery;

        if ($gallery?->exists) {
            $this->fill($gallery->only(['title', 'is_published', 'sort_order']));
            $this->current_image_path = $gallery->image_path;
        } else {
            $this->sort_order = (int) VillageGallery::query()->max('sort_order') + 1;
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:150',
            'is_published' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'image' => ($this->gallery?->exists ? 'nullable' : 'required').'|image|max:5120',
        ], [
            'image.required' => 'Foto wajib diunggah.',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => VillageGallery::generateUniqueSlug($this->title, $this->gallery?->id),
            'is_published' => $this->is_published,
            'sort_order' => $this->sort_order,
            'published_at' => $this->is_published
                ? ($this->gallery?->published_at ?? now())
                : null,
        ];

        if ($this->image) {
            if ($this->gallery?->image_path && ! str_starts_with($this->gallery->image_path, 'assets/')) {
                Storage::disk('public')->delete($this->gallery->image_path);
            }
            $data['image_path'] = $this->image->store('desa/galeri', 'public');
            $this->current_image_path = $data['image_path'];
            $this->image = null;
        }

        if ($this->gallery?->exists) {
            $this->gallery->update($data);
            $message = 'Foto berhasil diperbarui.';
        } else {
            $this->gallery = VillageGallery::query()->create($data);
            $message = 'Foto berhasil ditambahkan.';
        }

        $this->js('sessionStorage.setItem("ktPendingToast", '.json_encode(['message' => $message, 'type' => 'success']).')');
        $this->redirect(route('desa.galeri.index'), navigate: true);
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
                    {{ $gallery?->exists ? 'Edit Foto' : 'Tambah Foto' }}
                </h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    {{ $gallery?->exists ? 'Perbarui foto dokumentasi desa' : 'Unggah foto dokumentasi kegiatan desa' }}
                </p>
            </div>
            <a href="{{ route('desa.galeri.index') }}" class="kt-btn kt-btn-outline" wire:navigate>
                <i class="ki-filled ki-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="kt-container-fixed">
        <form wire:submit.prevent="save" class="grid gap-5 lg:gap-7.5">
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Informasi Foto</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Judul</label>
                        <input type="text" class="kt-input w-full" wire:model="title"
                            placeholder="Contoh: Gotong Royong Pembersihan Saluran Air" />
                        @error('title') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Foto</label>
                        <div class="grid gap-3">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                    class="max-h-64 w-full max-w-md rounded-lg object-cover" />
                            @elseif ($this->previewUrl($current_image_path))
                                <img src="{{ $this->previewUrl($current_image_path) }}" alt="Foto"
                                    class="max-h-64 w-full max-w-md rounded-lg object-cover" />
                            @endif
                            <input type="file" wire:model="image" accept="image/*" class="text-sm kt-input" />
                            <p class="text-xs text-muted-foreground">PNG/JPG, maks. 5MB.</p>
                            <div wire:loading wire:target="image" class="text-xs text-muted-foreground">Mengunggah foto...</div>
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
                <a href="{{ route('desa.galeri.index') }}" class="kt-btn kt-btn-outline" wire:navigate>Batal</a>
                <button type="submit" class="kt-btn kt-btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
