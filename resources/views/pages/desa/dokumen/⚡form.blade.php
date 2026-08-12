<?php

use App\Models\VillageDocument;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts::demo1.base')] class extends Component {
    use WithFileUploads;

    public ?VillageDocument $document = null;

    public string $title = '';
    public bool $is_published = true;
    public int $sort_order = 1;
    public ?string $current_file_path = null;

    public $file;

    public function mount(?VillageDocument $document = null): void
    {
        $this->document = $document;

        if ($document?->exists) {
            $this->fill($document->only(['title', 'is_published', 'sort_order']));
            $this->current_file_path = $document->file_path;
        } else {
            $this->sort_order = (int) VillageDocument::query()->max('sort_order') + 1;
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:150',
            'is_published' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'file' => ($this->document?->exists ? 'nullable' : 'required').'|file|mimes:pdf,jpg,jpeg,png,webp|max:20480',
        ], [
            'file.required' => 'File dokumen wajib diunggah.',
            'file.mimes' => 'File harus berupa PDF atau gambar (JPG/PNG/WebP).',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => VillageDocument::generateUniqueSlug($this->title, $this->document?->id),
            'is_published' => $this->is_published,
            'sort_order' => $this->sort_order,
            'published_at' => $this->is_published
                ? ($this->document?->published_at ?? now())
                : null,
        ];

        if ($this->file) {
            if ($this->document?->file_path && ! str_starts_with($this->document->file_path, 'assets/')) {
                Storage::disk('public')->delete($this->document->file_path);
            }
            $data['file_path'] = $this->file->store('desa/dokumen', 'public');
            $data['file_size'] = $this->file->getSize();
            $this->current_file_path = $data['file_path'];
            $this->file = null;
        }

        if ($this->document?->exists) {
            $this->document->update($data);
            $message = 'Dokumen berhasil diperbarui.';
        } else {
            $this->document = VillageDocument::query()->create($data);
            $message = 'Dokumen berhasil ditambahkan.';
        }

        $this->js('sessionStorage.setItem("ktPendingToast", '.json_encode(['message' => $message, 'type' => 'success']).')');
        $this->redirect(route('desa.dokumen.index'), navigate: true);
    }
};
?>

<div>
    <div class="kt-container-fixed">
        <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5 lg:items-end">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-mono">
                    {{ $document?->exists ? 'Edit Dokumen' : 'Tambah Dokumen' }}
                </h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    {{ $document?->exists ? 'Perbarui dokumen desa' : 'Unggah dokumen baru yang bisa diunduh warga' }}
                </p>
            </div>
            <a href="{{ route('desa.dokumen.index') }}" class="kt-btn kt-btn-outline" wire:navigate>
                <i class="ki-filled ki-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="kt-container-fixed">
        <form wire:submit.prevent="save" class="grid gap-5 lg:gap-7.5">
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Informasi Dokumen</h3>
                </div>
                <div class="kt-card-content grid gap-5 lg:grid-cols-2">
                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">Judul Dokumen</label>
                        <input type="text" class="kt-input w-full" wire:model="title"
                            placeholder="Contoh: Peraturan Desa No. 3 Tahun 2024" />
                        @error('title') <div class="kt-form-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="kt-form-item lg:col-span-2">
                        <label class="kt-form-label">File Dokumen (PDF / Gambar)</label>
                        <div class="grid gap-3">
                            @if ($current_file_path && ! $file)
                                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                    <i class="ki-filled ki-file text-base"></i>
                                    <span>File saat ini: {{ basename($current_file_path) }}</span>
                                    <a href="{{ $document?->fileUrl() }}" target="_blank" class="text-primary hover:underline">Lihat</a>
                                </div>
                            @endif
                            @if ($file)
                                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                    <i class="ki-filled ki-file-up text-base"></i>
                                    <span>File baru: {{ $file->getClientOriginalName() }}</span>
                                </div>
                            @endif
                            <input type="file" wire:model="file" accept=".pdf,image/*" class="text-sm" />
                            <p class="text-xs text-muted-foreground">PDF, JPG, PNG, atau WebP. Maksimal 20MB.</p>
                            <div wire:loading wire:target="file" class="text-xs text-muted-foreground">Mengunggah file...</div>
                        </div>
                        @error('file') <div class="kt-form-message">{{ $message }}</div> @enderror
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
                <a href="{{ route('desa.dokumen.index') }}" class="kt-btn kt-btn-outline" wire:navigate>Batal</a>
                <button type="submit" class="kt-btn kt-btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
