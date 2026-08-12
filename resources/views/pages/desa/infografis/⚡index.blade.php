<?php

use App\Models\VillageInfographic;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::demo1.base')] class extends Component {
    public function with(): array
    {
        return [
            'infographics' => VillageInfographic::query()
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
                <h1 class="text-xl font-medium leading-none text-mono">Infografis Desa</h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    Kelola infografis yang tampil di website desa
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('infografis.index') }}" target="_blank" class="kt-btn kt-btn-outline">
                    <i class="ki-filled ki-eye"></i>
                    Lihat di Website
                </a>
                <a href="{{ route('desa.infografis.create') }}" class="kt-btn kt-btn-primary" wire:navigate>
                    <i class="ki-filled ki-plus"></i>
                    Tambah Infografis
                </a>
            </div>
        </div>
    </div>

    <div class="kt-container-fixed">
        <div class="kt-card">
            <div class="kt-card-header flex-wrap gap-3">
                <h3 class="kt-card-title mt-3">Daftar Infografis</h3>
                <input type="text" placeholder="Cari judul infografis..." class="kt-input sm:w-48 mb-3"
                    data-kt-datatable-search="#infografis_datatable" />
            </div>

            <div id="infografis_datatable" class="kt-card-table" data-kt-datatable="true" data-kt-datatable-page-size="10">
                <div class="kt-table-wrapper kt-scrollable max-w-full overflow-auto">
                    <table class="kt-table kt-table-border min-w-[860px]" data-kt-datatable-table="true">
                        <thead>
                            <tr>
                                <th scope="col" class="w-[120px]" data-kt-datatable-column="image"
                                    data-kt-datatable-column-sort="false">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Gambar</span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[260px]" data-kt-datatable-column="title">
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
                                <th scope="col" class="w-[80px]" data-kt-datatable-column="order">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Urutan</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="text-end" data-kt-datatable-column="actions"
                                    data-kt-datatable-column-sort="false"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($infographics as $infographic)
                                <tr wire:key="infografis-{{ $infographic->id }}">
                                    <td>
                                        <img src="{{ $infographic->imageUrl() }}" alt="{{ $infographic->title }}"
                                            class="h-14 w-24 rounded-lg object-cover" />
                                    </td>
                                    <td>
                                        <p class="font-medium text-foreground">{{ $infographic->title }}</p>
                                        @if ($infographic->description)
                                            <p class="mt-0.5 line-clamp-1 text-xs text-muted-foreground">{{ $infographic->description }}</p>
                                        @endif
                                    </td>
                                    <td class="text-muted-foreground">{{ $infographic->formattedDate() }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('desa.infografis.toggle', $infographic) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="kt-badge kt-badge-sm {{ $infographic->is_published ? 'kt-badge-success' : 'kt-badge-secondary' }} kt-badge-outline cursor-pointer">
                                                {{ $infographic->is_published ? 'Publikasi' : 'Draft' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-muted-foreground">{{ $infographic->sort_order }}</td>
                                    <td class="text-end">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ $infographic->imageUrl() }}" target="_blank"
                                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" title="Lihat Gambar">
                                                <i class="ki-filled ki-eye"></i>
                                            </a>
                                            <a href="{{ route('desa.infografis.edit', $infographic) }}"
                                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" wire:navigate title="Edit">
                                                <i class="ki-filled ki-notepad-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('desa.infografis.destroy', $infographic) }}" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus infografis ini?')">
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
                                        Belum ada infografis.
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
