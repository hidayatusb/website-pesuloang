<?php

use App\Models\VillageOfficial;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts::demo1.base')] class extends Component {
    use WithFileUploads;

    public ?VillageOfficial $official = null;

    public string $name = '';
    public string $position = '';
    public bool $is_active = true;
    public int $sort_order = 1;
    public ?string $current_photo_path = null;

    public $photo;

    public function mount(?VillageOfficial $official = null): void
    {
        $this->official = $official;

        if ($official?->exists) {
            $this->fill($official->only(['name', 'position', 'is_active', 'sort_order']));
            $this->current_photo_path = $official->photo_path;
        } else {
            $this->sort_order = (int) VillageOfficial::query()->max('sort_order') + 1;
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'photo' => 'nullable|image|max:5120',
        ]);

        $data = [
            'name' => $this->name,
            'position' => $this->position,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->photo) {
            if ($this->official?->photo_path && ! str_starts_with($this->official->photo_path, 'assets/')) {
                Storage::disk('public')->delete($this->official->photo_path);
            }
            $data['photo_path'] = $this->photo->store('desa/aparatur', 'public');
            $this->current_photo_path = $data['photo_path'];
            $this->photo = null;
        }

        if ($this->official?->exists) {
            $this->official->update($data);
            $message = 'Aparatur berhasil diperbarui.';
        } else {
            $this->official = VillageOfficial::query()->create($data);
            $message = 'Aparatur berhasil ditambahkan.';
        }

        $this->js('sessionStorage.setItem("ktPendingToast", '.json_encode(['message' => $message, 'type' => 'success']).')');
        $this->redirect(route('desa.aparatur.index'), navigate: true);
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
                    {{ $official?->exists ? 'Edit Aparatur' : 'Tambah Aparatur' }}
                </h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    {{ $official?->exists ? 'Perbarui profil perangkat desa' : 'Tambahkan profil perangkat desa baru' }}
                </p>
            </div>
            <a href="{{ route('desa.aparatur.index') }}" class="kt-btn kt-btn-outline" wire:navigate>
                <i class="ki-filled ki-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="kt-container-fixed">
        <form wire:submit.prevent="save" class="grid gap-5 lg:gap-7.5">
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Profil Aparatur</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item">
                        <label class="kt-form-label">Nama Lengkap</label>
                        <input type="text" class="kt-input w-full" wire:model="name" placeholder="Nama lengkap beserta gelar" />
                        @error('name') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Jabatan</label>
                        <input type="text" class="kt-input w-full" wire:model="position" placeholder="Contoh: Kepala Desa" />
                        @error('position') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Foto</label>
                        <div class="grid gap-3">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" width="200" alt="Preview"
                                    class="size-40 rounded-xl object-cover" />
                            @elseif ($this->previewUrl($current_photo_path))
                                <img src="{{ $this->previewUrl($current_photo_path) }}" width="200" alt="Foto"
                                    class="size-40 rounded-xl object-cover" />
                            @endif
                            <input type="file" wire:model="photo" accept="image/*" class="text-sm kt-input" />
                            <p class="text-xs text-muted-foreground">PNG/JPG, maks. 5MB. Disarankan foto persegi (1:1). Kosongkan untuk memakai avatar bawaan.</p>
                            <div wire:loading wire:target="photo" class="text-xs text-muted-foreground">Mengunggah foto...</div>
                        </div>
                        @error('photo') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Urutan</label>
                        <input type="number" min="0" class="kt-input w-full" wire:model="sort_order" />
                        <p class="mt-1 text-xs text-muted-foreground">Urutan kecil tampil lebih dulu (Kepala Desa = 1).</p>
                        @error('sort_order') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item flex flex-col justify-end gap-3">
                        <label class="kt-label">
                            <input class="kt-checkbox kt-checkbox-sm" type="checkbox" wire:model="is_active" />
                            <span class="kt-checkbox-label">Tampilkan di website</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('desa.aparatur.index') }}" class="kt-btn kt-btn-outline" wire:navigate>Batal</a>
                <button type="submit" class="kt-btn kt-btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
