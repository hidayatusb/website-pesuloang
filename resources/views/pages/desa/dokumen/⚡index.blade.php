<?php

use App\Models\VillageDocument;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::demo1.base')] class extends Component {
    public function with(): array
    {
        return [
            'documents' => VillageDocument::query()
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
                <h1 class="text-xl font-medium leading-none text-mono">Dokumen Desa</h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    Kelola dokumen yang bisa diunduh warga dari website
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dokumen.index') }}" target="_blank" class="kt-btn kt-btn-outline">
                    <i class="ki-filled ki-eye"></i>
                    Lihat di Website
                </a>
                <a href="{{ route('desa.dokumen.create') }}" class="kt-btn kt-btn-primary" wire:navigate>
                    <i class="ki-filled ki-plus"></i>
                    Tambah Dokumen
                </a>
            </div>
        </div>
    </div>

    <div class="kt-container-fixed">
        <div class="kt-card">
            <div class="kt-card-header flex-wrap gap-3">
                <h3 class="kt-card-title">Daftar Dokumen</h3>
                <input type="text" placeholder="Cari judul dokumen..." class="kt-input sm:w-48"
                    data-kt-datatable-search="#dokumen_datatable" />
            </div>

            <div id="dokumen_datatable" class="kt-card-table" data-kt-datatable="true" data-kt-datatable-page-size="10">
                <div class="kt-table-wrapper kt-scrollable max-w-full overflow-auto">
                    <table class="kt-table kt-table-border min-w-[860px]" data-kt-datatable-table="true">
                        <thead>
                            <tr>
                                <th scope="col" class="min-w-[280px]" data-kt-datatable-column="title">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Judul</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="w-[90px]" data-kt-datatable-column="type">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Jenis</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="w-[100px]" data-kt-datatable-column="size">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Ukuran</span>
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
                            @forelse ($documents as $document)
                                <tr wire:key="dokumen-{{ $document->id }}">
                                    <td>
                                        <p class="font-medium text-foreground">{{ $document->title }}</p>
                                    </td>
                                    <td>
                                        <span class="kt-badge kt-badge-sm {{ $document->fileExtension() === 'pdf' ? 'kt-badge-destructive' : 'kt-badge-info' }} kt-badge-outline">
                                            {{ $document->fileTypeLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-muted-foreground">{{ $document->formattedSize() }}</td>
                                    <td class="text-muted-foreground">{{ $document->formattedDate() }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('desa.dokumen.toggle', $document) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="kt-badge kt-badge-sm {{ $document->is_published ? 'kt-badge-success' : 'kt-badge-secondary' }} kt-badge-outline cursor-pointer">
                                                {{ $document->is_published ? 'Publikasi' : 'Draft' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ $document->fileUrl() }}" target="_blank"
                                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" title="Lihat / Unduh">
                                                <i class="ki-filled ki-file-down"></i>
                                            </a>
                                            <a href="{{ route('desa.dokumen.edit', $document) }}"
                                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" wire:navigate title="Edit">
                                                <i class="ki-filled ki-notepad-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('desa.dokumen.destroy', $document) }}" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
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
                                    <td colspan="6" class="py-10 text-center text-muted-foreground">
                                        Belum ada dokumen.
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
