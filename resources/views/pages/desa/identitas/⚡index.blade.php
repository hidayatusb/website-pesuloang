<?php

use App\Models\VillageIdentity;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;

new #[Layout('layouts::demo1.base')] class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $kecamatan = '';
    public string $kabupaten = '';
    public string $welcome_text = '';
    public string $tagline = '';
    public string $about_label = '';
    public string $about_title = '';
    public string $about_description = '';
    public string $address = '';
    public string $phone = '';
    public string $email = '';
    public string $facebook_url = '';
    public string $instagram_url = '';
    public string $youtube_url = '';

    public ?string $current_logo_path = null;
    public ?string $current_hero_image_path = null;
    public ?string $current_about_image_path = null;

    #[Validate('nullable|image|max:2048')]
    public $logo;

    #[Validate('nullable|image|max:5120')]
    public $hero_image;

    #[Validate('nullable|image|max:5120')]
    public $about_image;

    public function mount(): void
    {
        $identity = VillageIdentity::query()->firstOrFail();

        $this->fill($identity->only([
            'name', 'kecamatan', 'kabupaten', 'welcome_text', 'tagline',
            'about_label', 'about_title', 'about_description',
            'address', 'phone', 'email',
            'facebook_url', 'instagram_url', 'youtube_url',
        ]));

        $this->current_logo_path = $identity->logo_path;
        $this->current_hero_image_path = $identity->hero_image_path;
        $this->current_about_image_path = $identity->about_image_path;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'welcome_text' => 'required|string|max:255',
            'tagline' => 'required|string|max:1000',
            'about_label' => 'required|string|max:255',
            'about_title' => 'required|string|max:255',
            'about_description' => 'required|string',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'facebook_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:5120',
            'about_image' => 'nullable|image|max:5120',
        ]);

        $identity = VillageIdentity::query()->firstOrFail();

        $data = [
            'name' => $this->name,
            'kecamatan' => $this->kecamatan,
            'kabupaten' => $this->kabupaten,
            'welcome_text' => $this->welcome_text,
            'tagline' => $this->tagline,
            'about_label' => $this->about_label,
            'about_title' => $this->about_title,
            'about_description' => $this->about_description,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'youtube_url' => $this->youtube_url,
        ];

        if ($this->logo) {
            if ($identity->logo_path && ! str_starts_with($identity->logo_path, 'assets/')) {
                Storage::disk('public')->delete($identity->logo_path);
            }
            $data['logo_path'] = $this->logo->store('desa/logo', 'public');
            $this->current_logo_path = $data['logo_path'];
            $this->logo = null;
        }

        if ($this->hero_image) {
            if ($identity->hero_image_path && ! str_starts_with($identity->hero_image_path, 'assets/')) {
                Storage::disk('public')->delete($identity->hero_image_path);
            }
            $data['hero_image_path'] = $this->hero_image->store('desa/hero', 'public');
            $this->current_hero_image_path = $data['hero_image_path'];
            $this->hero_image = null;
        }

        if ($this->about_image) {
            if ($identity->about_image_path && ! str_starts_with($identity->about_image_path, 'assets/')) {
                Storage::disk('public')->delete($identity->about_image_path);
            }
            $data['about_image_path'] = $this->about_image->store('desa/about', 'public');
            $this->current_about_image_path = $data['about_image_path'];
            $this->about_image = null;
        }

        $identity->update($data);
        VillageIdentity::clearCache();

        $this->dispatch('show-toast', message: 'Identitas desa berhasil disimpan.', type: 'success');
    }

    public function removeLogo(): void
    {
        $identity = VillageIdentity::query()->firstOrFail();

        if ($identity->logo_path && ! str_starts_with($identity->logo_path, 'assets/')) {
            Storage::disk('public')->delete($identity->logo_path);
        }

        $identity->update(['logo_path' => null]);
        VillageIdentity::clearCache();

        $this->current_logo_path = null;
        $this->dispatch('show-toast', message: 'Logo berhasil dihapus.', type: 'success');
    }

    public function previewUrl(?string $path): ?string
    {
        return (new VillageIdentity)->imageUrl($path);
    }
};
?>

<div>
    <div class="kt-container-fixed">
        <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5 lg:items-end">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-mono">Identitas Desa</h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    Kelola logo, nama desa, dan informasi yang tampil di website
                </p>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="kt-btn kt-btn-outline">
                <i class="ki-filled ki-eye"></i>
                Lihat Website
            </a>
        </div>
    </div>

    <div class="kt-container-fixed">
        <form wire:submit.prevent="save" class="grid gap-5 lg:gap-7.5">
            {{-- Identitas Utama --}}
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Identitas Utama</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Logo Desa</label>
                        <div class="flex flex-wrap items-center gap-5">
                            <div class="flex size-20 items-center justify-center overflow-hidden rounded-full border border-border bg-accent/40">
                                @if ($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview logo" class="size-full object-cover" />
                                @elseif ($this->previewUrl($current_logo_path))
                                    <img src="{{ $this->previewUrl($current_logo_path) }}" alt="Logo desa" class="size-full object-cover" />
                                @else
                                    <i class="ki-filled ki-home-2 text-2xl text-muted-foreground"></i>
                                @endif
                            </div>
                            <div class="flex flex-col gap-2">
                                <input type="file" wire:model="logo" accept="image/*" class="text-sm kt-input" />
                                <p class="text-xs text-muted-foreground">PNG/JPG, maks. 2MB. Tampil di navbar & footer website.</p>
                                @if ($current_logo_path)
                                    <button type="button" wire:click="removeLogo" class="kt-btn kt-btn-sm kt-btn-outline w-fit">
                                        Hapus Logo
                                    </button>
                                @endif
                            </div>
                        </div>
                        @error('logo') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Nama Desa</label>
                        <input type="text" class="kt-input w-full" wire:model="name" placeholder="Desa Sukamaju" />
                        @error('name') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Teks Sambutan Hero</label>
                        <input type="text" class="kt-input w-full" wire:model="welcome_text" placeholder="Selamat Datang di" />
                        @error('welcome_text') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Kecamatan</label>
                        <input type="text" class="kt-input w-full" wire:model="kecamatan" placeholder="Kec. Cikarang Utara" />
                        @error('kecamatan') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Kabupaten</label>
                        <input type="text" class="kt-input w-full" wire:model="kabupaten" placeholder="Kab. Bekasi" />
                        @error('kabupaten') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Tagline / Visi Singkat</label>
                        <textarea class="kt-textarea w-full" wire:model="tagline" rows="3"></textarea>
                        @error('tagline') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Hero --}}
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Hero</h3>
                </div>
                <div class="kt-card-content">
                    <div class="kt-form-item">
                        <label class="kt-form-label">Gambar Hero (Banner Utama)</label>
                        <div class="grid gap-3">
                            @if ($hero_image)
                                <img src="{{ $hero_image->temporaryUrl() }}" width="200" alt="Preview hero" class=" w-full rounded-lg object-cover" />
                            @elseif ($this->previewUrl($current_hero_image_path))
                                <img src="{{ $this->previewUrl($current_hero_image_path) }}" width="200" alt="Hero saat ini" class=" w-full rounded-lg object-cover" />
                            @endif
                            <input type="file" wire:model="hero_image" accept="image/*" class="text-sm kt-input" />
                            <p class="text-xs text-muted-foreground">PNG/JPG, maks. 5MB.</p>
                        </div>
                        @error('hero_image') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Tentang Kami --}}
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Tentang Kami</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item">
                        <label class="kt-form-label">Label Section</label>
                        <input type="text" class="kt-input w-full" wire:model="about_label" placeholder="Tentang Kami" />
                        @error('about_label') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Judul Section</label>
                        <input type="text" class="kt-input w-full" wire:model="about_title" />
                        @error('about_title') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Deskripsi</label>
                        <textarea class="kt-textarea w-full" wire:model="about_description" rows="5"></textarea>
                        @error('about_description') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Gambar Section Profil</label>
                        <div class="grid gap-3">
                            @if ($about_image)
                                <img src="{{ $about_image->temporaryUrl() }}" alt="Preview profil" class="max-h-40 w-full rounded-lg object-cover" />
                            @elseif ($this->previewUrl($current_about_image_path))
                                <img src="{{ $this->previewUrl($current_about_image_path) }}" alt="Gambar profil" class="max-h-40 w-full rounded-lg object-cover" />
                            @endif
                            <input type="file" wire:model="about_image" accept="image/*" class="text-sm" />
                            <p class="text-xs text-muted-foreground">PNG/JPG, maks. 5MB.</p>
                        </div>
                        @error('about_image') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Kontak --}}
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Kontak & Sosial Media</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Alamat Lengkap</label>
                        <textarea class="kt-textarea w-full" wire:model="address" rows="2"></textarea>
                        @error('address') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Telepon</label>
                        <input type="text" class="kt-input w-full" wire:model="phone" placeholder="(021) 1234-5678" />
                        @error('phone') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Email</label>
                        <input type="email" class="kt-input w-full" wire:model="email" placeholder="info@desasukamaju.go.id" />
                        @error('email') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Facebook URL</label>
                        <input type="text" class="kt-input w-full" wire:model="facebook_url" placeholder="https://facebook.com/..." />
                        @error('facebook_url') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Instagram URL</label>
                        <input type="text" class="kt-input w-full" wire:model="instagram_url" placeholder="https://instagram.com/..." />
                        @error('instagram_url') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">YouTube URL</label>
                        <input type="text" class="kt-input w-full" wire:model="youtube_url" placeholder="https://youtube.com/..." />
                        @error('youtube_url') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="kt-btn kt-btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
