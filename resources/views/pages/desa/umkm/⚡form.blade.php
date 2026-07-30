<?php

use App\Models\VillageUmkm;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts::demo1.base')] class extends Component {
    use WithFileUploads;

    public ?VillageUmkm $umkm = null;

    public string $name = '';
    public string $category = 'makanan';
    public string $owner_name = '';
    public string $excerpt = '';
    public string $description = '';
    public string $phone = '';
    public string $whatsapp = '';
    public string $address = '';
    public string $products = '';
    public bool $is_published = true;
    public bool $is_featured = false;
    public int $sort_order = 1;
    public ?string $current_image_path = null;

    public $image;

    public function mount(?VillageUmkm $umkm = null): void
    {
        $this->umkm = $umkm;

        if ($umkm?->exists) {
            $this->fill($umkm->only([
                'name', 'category', 'owner_name', 'excerpt', 'description',
                'phone', 'whatsapp', 'address', 'products',
                'is_published', 'is_featured', 'sort_order',
            ]));
            $this->current_image_path = $umkm->image_path;
        } else {
            $this->sort_order = (int) VillageUmkm::query()->max('sort_order') + 1;
        }
    }

    public function with(): array
    {
        return ['categories' => VillageUmkm::categories()];
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|in:'.implode(',', array_keys(VillageUmkm::categories())),
            'owner_name' => 'required|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'description' => 'required|string',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'products' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => VillageUmkm::generateUniqueSlug($this->name, $this->umkm?->id),
            'category' => $this->category,
            'owner_name' => $this->owner_name,
            'excerpt' => $this->excerpt ?: null,
            'description' => $this->description,
            'phone' => $this->phone ?: null,
            'whatsapp' => $this->whatsapp ?: null,
            'address' => $this->address ?: null,
            'products' => $this->products ?: null,
            'is_published' => $this->is_published,
            'is_featured' => $this->is_featured,
            'sort_order' => $this->sort_order,
            'published_at' => $this->is_published
                ? ($this->umkm?->published_at ?? now())
                : null,
        ];

        if ($this->image) {
            if ($this->umkm?->image_path && ! str_starts_with($this->umkm->image_path, 'assets/')) {
                Storage::disk('public')->delete($this->umkm->image_path);
            }
            $data['image_path'] = $this->image->store('desa/umkm', 'public');
            $this->current_image_path = $data['image_path'];
            $this->image = null;
        }

        if ($this->umkm?->exists) {
            $this->umkm->update($data);
            $message = 'UMKM berhasil diperbarui.';
        } else {
            $this->umkm = VillageUmkm::query()->create($data);
            $message = 'UMKM berhasil ditambahkan.';
        }

        $this->js('sessionStorage.setItem("ktPendingToast", '.json_encode(['message' => $message, 'type' => 'success']).')');
        $this->redirect(route('desa.umkm.index'), navigate: true);
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
                    {{ $umkm?->exists ? 'Edit UMKM' : 'Tambah UMKM' }}
                </h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    {{ $umkm?->exists ? 'Perbarui profil usaha desa' : 'Tambahkan profil usaha desa baru' }}
                </p>
            </div>
            <a href="{{ route('desa.umkm.index') }}" class="kt-btn kt-btn-outline" wire:navigate>
                <i class="ki-filled ki-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="kt-container-fixed">
        <form wire:submit.prevent="save" class="grid gap-5 lg:gap-7.5">
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Informasi Usaha</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Nama Usaha</label>
                        <input type="text" class="kt-input w-full" wire:model="name" placeholder="Warung Makan Bu Siti" />
                        @error('name') <div class="kt-form-message">{{ $message }}</div> @enderror
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
                        <label class="kt-form-label">Nama Pemilik</label>
                        <input type="text" class="kt-input w-full" wire:model="owner_name" placeholder="Nama pemilik usaha" />
                        @error('owner_name') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Ringkasan</label>
                        <textarea class="kt-textarea" wire:model="excerpt" rows="2"
                            placeholder="Deskripsi singkat untuk kartu UMKM"></textarea>
                        @error('excerpt') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <x-rich-editor
                            model="description"
                            :value="$description"
                            label="Deskripsi Lengkap"
                            placeholder="Profil usaha, jam operasional, keunggulan"
                            min-height="320px"
                        />
                        @error('description') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Produk / Layanan Unggulan</label>
                        <textarea class="kt-textarea" wire:model="products" rows="2"
                            placeholder="Daftar produk atau layanan yang ditawarkan"></textarea>
                        @error('products') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Kontak & Lokasi</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item">
                        <label class="kt-form-label">Telepon</label>
                        <input type="text" class="kt-input w-full" wire:model="phone" placeholder="0812-3456-7890" />
                        @error('phone') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">WhatsApp</label>
                        <input type="text" class="kt-input w-full" wire:model="whatsapp" placeholder="081234567890" />
                        @error('whatsapp') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Alamat</label>
                        <input type="text" class="kt-input w-full" wire:model="address" placeholder="Dusun, RT/RW, atau alamat lengkap" />
                        @error('address') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Gambar & Publikasi</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Foto Usaha</label>
                        <div class="grid gap-3">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="max-h-48 w-full max-w-md rounded-lg object-cover" />
                            @elseif ($this->previewUrl($current_image_path))
                                <img src="{{ $this->previewUrl($current_image_path) }}" alt="Foto usaha" class="max-h-48 w-full max-w-md rounded-lg object-cover" />
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
                            <span class="kt-checkbox-label">Tampilkan sebagai UMKM unggulan di beranda</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('desa.umkm.index') }}" class="kt-btn kt-btn-outline" wire:navigate>Batal</a>
                <button type="submit" class="kt-btn kt-btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
