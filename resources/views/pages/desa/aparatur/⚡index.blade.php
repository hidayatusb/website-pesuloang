<?php

use App\Models\VillageOfficial;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::demo1.base')] class extends Component {
    public function with(): array
    {
        return [
            'officials' => VillageOfficial::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ];
    }
};
?>

<div>
    <div class="kt-container-fixed">
        <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5 lg:items-end">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-mono">Aparatur Desa</h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    Kelola profil perangkat desa yang tampil di website
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('home') }}#aparatur" target="_blank" class="kt-btn kt-btn-outline">
                    <i class="ki-filled ki-eye"></i>
                    Lihat di Website
                </a>
                <a href="{{ route('desa.aparatur.create') }}" class="kt-btn kt-btn-primary" wire:navigate>
                    <i class="ki-filled ki-plus"></i>
                    Tambah Aparatur
                </a>
            </div>
        </div>
    </div>

    <div class="kt-container-fixed">
        <div class="kt-card">
            <div class="kt-card-header flex-wrap gap-3">
                <h3 class="kt-card-title mt-3">Daftar Aparatur</h3>
                <input type="text" placeholder="Cari nama atau jabatan..." class="kt-input sm:w-48 mb-3"
                    data-kt-datatable-search="#aparatur_datatable" />
            </div>

            <div id="aparatur_datatable" class="kt-card-table" data-kt-datatable="true" data-kt-datatable-page-size="10">
                <div class="kt-table-wrapper kt-scrollable max-w-full overflow-auto">
                    <table class="kt-table kt-table-border min-w-[720px]" data-kt-datatable-table="true">
                        <thead>
                            <tr>
                                <th scope="col" class="min-w-[240px]" data-kt-datatable-column="name">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Nama</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[180px]" data-kt-datatable-column="position">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Jabatan</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="w-[80px]" data-kt-datatable-column="order">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Urutan</span>
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
                            @forelse ($officials as $official)
                                <tr wire:key="aparatur-{{ $official->id }}">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $official->photoUrl() }}" alt="{{ $official->name }}"
                                                class="size-10 rounded-full object-cover" />
                                            <p class="font-medium text-foreground">{{ $official->name }}</p>
                                        </div>
                                    </td>
                                    <td class="text-muted-foreground">{{ $official->position }}</td>
                                    <td class="text-muted-foreground">{{ $official->sort_order }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('desa.aparatur.toggle', $official) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="kt-badge kt-badge-sm {{ $official->is_active ? 'kt-badge-success' : 'kt-badge-secondary' }} kt-badge-outline cursor-pointer">
                                                {{ $official->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('desa.aparatur.edit', $official) }}"
                                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" wire:navigate title="Edit">
                                                <i class="ki-filled ki-notepad-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('desa.aparatur.destroy', $official) }}" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus aparatur ini?')">
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
                                        Belum ada data aparatur desa.
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
