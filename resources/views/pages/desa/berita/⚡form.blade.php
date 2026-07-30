<?php

use App\Models\VillagePost;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts::demo1.base')] class extends Component {
    use WithFileUploads;

    public ?VillagePost $post = null;

    public string $title = '';
    public string $type = 'berita';
    public string $category = '';
    public string $excerpt = '';
    public string $content = '';
    public string $author_name = '';
    public bool $is_published = true;
    public ?string $current_image_path = null;

    public $image;

    public function mount(?VillagePost $post = null): void
    {
        $this->post = $post;

        if ($post?->exists) {
            $this->fill($post->only([
                'title', 'type', 'category', 'excerpt', 'content', 'author_name', 'is_published',
            ]));
            $this->current_image_path = $post->image_path;
        } else {
            $this->author_name = Auth::user()->name;
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:berita,pengumuman',
            'category' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'author_name' => 'nullable|string|max:100',
            'content' => 'required|string',
            'is_published' => 'boolean',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => VillagePost::generateUniqueSlug($this->title, $this->post?->id),
            'type' => $this->type,
            'category' => $this->category ?: ($this->type === 'pengumuman' ? 'Pengumuman' : null),
            'excerpt' => $this->excerpt,
            'author_name' => $this->author_name ?: Auth::user()->name,
            'content' => $this->content,
            'is_published' => $this->is_published,
            'published_at' => $this->is_published
                ? ($this->post?->published_at ?? now())
                : null,
        ];

        if ($this->image) {
            if ($this->post?->image_path && ! str_starts_with($this->post->image_path, 'assets/')) {
                Storage::disk('public')->delete($this->post->image_path);
            }
            $data['image_path'] = $this->image->store('desa/berita', 'public');
            $this->current_image_path = $data['image_path'];
            $this->image = null;
        }

        if ($this->post?->exists) {
            $this->post->update($data);
            $message = 'Konten berhasil diperbarui.';
        } else {
            $data['user_id'] = Auth::id();
            $this->post = VillagePost::query()->create($data);
            $message = 'Konten berhasil ditambahkan.';
        }

        $this->js('sessionStorage.setItem("ktPendingToast", '.json_encode(['message' => $message, 'type' => 'success']).')');
        $this->redirect(route('desa.berita.index'), navigate: true);
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
                    {{ $post?->exists ? 'Edit Konten' : 'Tambah Konten' }}
                </h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    {{ $post?->exists ? 'Perbarui berita atau pengumuman' : 'Buat berita atau pengumuman baru' }}
                </p>
            </div>
            <a href="{{ route('desa.berita.index') }}" class="kt-btn kt-btn-outline" wire:navigate>
                <i class="ki-filled ki-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="kt-container-fixed">
        <form wire:submit.prevent="save" class="grid gap-5 lg:gap-7.5">
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Informasi Konten</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Judul</label>
                        <input type="text" class="kt-input w-full" wire:model="title" placeholder="Judul berita atau pengumuman" />
                        @error('title') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Tipe</label>
                        <select class="kt-input w-full" wire:model.live="type">
                            <option value="berita">Berita</option>
                            <option value="pengumuman">Pengumuman</option>
                        </select>
                        @error('type') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Kategori</label>
                        <input type="text" class="kt-input w-full" wire:model="category"
                            placeholder="{{ $type === 'pengumuman' ? 'Pengumuman' : 'Pembangunan, Kegiatan, dll.' }}" />
                        @error('category') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Penulis</label>
                        <input type="text" class="kt-input w-full" wire:model="author_name"
                            placeholder="Nama penulis yang tampil di website" />
                        <p class="mt-1 text-xs text-muted-foreground">
                            @if ($post?->exists && $post->author)
                                Akun pembuat: {{ $post->author->name }}
                            @else
                                Default menggunakan nama akun Anda: {{ Auth::user()->name }}
                            @endif
                        </p>
                        @error('author_name') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Ringkasan</label>
                        <textarea class="kt-input w-full min-h-[80px]" wire:model="excerpt" rows="2"
                            placeholder="Ringkasan singkat untuk kartu berita"></textarea>
                        @error('excerpt') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <x-rich-editor
                            model="content"
                            :value="$content"
                            label="Konten"
                            placeholder="Isi lengkap berita atau pengumuman"
                            min-height="320px"
                        />
                        @error('content') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Gambar & Publikasi</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Gambar Sampul</label>
                        <div class="grid gap-3">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="max-h-48 w-full max-w-md rounded-lg object-cover" />
                            @elseif ($this->previewUrl($current_image_path))
                                <img src="{{ $this->previewUrl($current_image_path) }}" alt="Sampul" class="max-h-48 w-full max-w-md rounded-lg object-cover" />
                            @endif
                            <input type="file" wire:model="image" accept="image/*" class="text-sm" />
                            <p class="text-xs text-muted-foreground">PNG/JPG, maks. 5MB.</p>
                        </div>
                        @error('image') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-label">
                            <input class="kt-checkbox kt-checkbox-sm" type="checkbox" wire:model="is_published" />
                            <span class="kt-checkbox-label">Publikasikan ke website</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('desa.berita.index') }}" class="kt-btn kt-btn-outline" wire:navigate>Batal</a>
                <button type="submit" class="kt-btn kt-btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
