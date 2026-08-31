<?php

use App\Models\VillageGallery;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::demo1.base')] class extends Component {
    public function with(): array
    {
        return [
            'galleries' => VillageGallery::query()
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->get(),
        ];
    }
};
?>

<div>
    <div class="kt-container-fixed">
        <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5 lg:items-end">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-mono">Dokumentasi / Galeri</h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    Kelola foto dokumentasi kegiatan desa yang tampil di website
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('galeri.index') }}" target="_blank" class="kt-btn kt-btn-outline">
                    <i class="ki-filled ki-eye"></i>
                    Lihat di Website
                </a>
                <a href="{{ route('desa.galeri.create') }}" class="kt-btn kt-btn-primary" wire:navigate>
                    <i class="ki-filled ki-plus"></i>
                    Tambah Foto
                </a>
            </div>
        </div>
    </div>

    <div class="kt-container-fixed">
        <div class="kt-card">
            <div class="kt-card-header flex-wrap gap-3">
                <h3 class="kt-card-title mt-3">Daftar Foto</h3>
                <input type="text" placeholder="Cari judul foto..." class="kt-input sm:w-48 mb-3"
                    data-kt-datatable-search="#galeri_datatable" />
            </div>

            <div id="galeri_datatable" class="kt-card-table" data-kt-datatable="true" data-kt-datatable-page-size="10">
                <div class="kt-table-wrapper kt-scrollable max-w-full overflow-auto">
                    <table class="kt-table kt-table-border min-w-[760px]" data-kt-datatable-table="true">
                        <thead>
                            <tr>
                                <th scope="col" class="w-[120px]" data-kt-datatable-column="image"
                                    data-kt-datatable-column-sort="false">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Foto</span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[280px]" data-kt-datatable-column="title">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Judul</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[130px]" data-kt-datatable-column="date">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Tanggal</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[100px]" data-kt-datatable-column="status">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Status</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="text-end" data-kt-datatable-column="actions"
                                    data-kt-datatable-column-sort="false"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($galleries as $gallery)
                                <tr wire:key="galeri-{{ $gallery->id }}">
                                    <td>
                                        <img src="{{ $gallery->imageUrl() }}" alt="{{ $gallery->title }}"
                                            class="h-14 w-24 rounded-lg object-cover" />
                                    </td>
                                    <td>
                                        <p class="font-medium text-foreground">{{ $gallery->title }}</p>
                                    </td>
                                    <td class="text-muted-foreground">{{ $gallery->formattedDate() }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('desa.galeri.toggle', $gallery) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="kt-badge kt-badge-sm {{ $gallery->is_published ? 'kt-badge-success' : 'kt-badge-secondary' }} kt-badge-outline cursor-pointer">
                                                {{ $gallery->is_published ? 'Publikasi' : 'Draft' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ $gallery->imageUrl() }}" target="_blank"
                                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" title="Lihat Foto">
                                                <i class="ki-filled ki-eye"></i>
                                            </a>
                                            <a href="{{ route('desa.galeri.edit', $gallery) }}"
                                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" wire:navigate title="Edit">
                                                <i class="ki-filled ki-notepad-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('desa.galeri.destroy', $gallery) }}" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost text-red-500" title="Hapus">
                                                    <i class="ki-filled ki-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-muted-foreground">
                                        Belum ada foto dokumentasi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="kt-datatable-toolbar">
                    <div class="kt-datatable-length">
                        Tampilkan
                        <select class="kt-select kt-select-sm w-16" name="perpage" data-kt-datatable-size="true"></select>
                        per halaman
                    </div>
                    <div class="kt-datatable-info">
                        <span data-kt-datatable-info="true"></span>
                        <div class="kt-datatable-pagination" data-kt-datatable-pagination="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
