<?php

use App\Models\VillageUmkm;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::demo1.base')] class extends Component {
    public string $filter = 'semua';

    public function updatingFilter(): void
    {
        $this->dispatch('umkm-filter-changed');
    }

    public function with(): array
    {
        $umkms = VillageUmkm::query()
            ->when($this->filter !== 'semua', fn ($q) => $q->where('category', $this->filter))
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return [
            'umkms' => $umkms,
            'categories' => VillageUmkm::categories(),
        ];
    }
};
?>

<div>
    <div class="kt-container-fixed">
        <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5 lg:items-end">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-mono">UMKM Desa</h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    Kelola profil usaha mikro desa yang tampil di website
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('umkm.index') }}" target="_blank" class="kt-btn kt-btn-outline">
                    <i class="ki-filled ki-eye"></i>
                    Lihat di Website
                </a>
                <a href="{{ route('desa.umkm.create') }}" class="kt-btn kt-btn-primary" wire:navigate>
                    <i class="ki-filled ki-plus"></i>
                    Tambah UMKM
                </a>
            </div>
        </div>
    </div>

    <div class="kt-container-fixed">
        <div class="kt-card">
            <div class="kt-card-header flex-wrap gap-3">
                <div class="flex flex-wrap gap-2 mt-3">
                    <button type="button" wire:click="$set('filter', 'semua')"
                        class="kt-btn kt-btn-sm {{ $filter === 'semua' ? 'kt-btn-primary' : 'kt-btn-outline' }}">
                        Semua
                    </button>
                    @foreach ($categories as $value => $label)
                        <button type="button" wire:click="$set('filter', '{{ $value }}')"
                            class="kt-btn kt-btn-sm {{ $filter === $value ? 'kt-btn-primary' : 'kt-btn-outline' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                <input type="text" placeholder="Cari nama usaha..." class="kt-input sm:w-48 mb-3"
                    data-kt-datatable-search="#umkm_datatable" />
            </div>

            <div id="umkm_datatable" class="kt-card-table" data-kt-datatable="true" data-kt-datatable-page-size="10"
                wire:key="umkm-datatable-{{ $filter }}">
                <div class="kt-table-wrapper kt-scrollable max-w-full overflow-auto">
                    <table class="kt-table kt-table-border min-w-[960px]" data-kt-datatable-table="true">
                        <thead>
                            <tr>
                                <th scope="col" class="min-w-[220px]" data-kt-datatable-column="name">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Nama Usaha</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[120px]" data-kt-datatable-column="category">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Kategori</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[140px]" data-kt-datatable-column="owner">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Pemilik</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[100px]" data-kt-datatable-column="status">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Status</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[90px]" data-kt-datatable-column="featured">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Unggulan</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="text-end" data-kt-datatable-column="actions"
                                    data-kt-datatable-column-sort="false"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($umkms as $umkm)
                                <tr wire:key="umkm-{{ $umkm->id }}">
                                    <td>
                                        <p class="font-medium text-foreground">{{ $umkm->name }}</p>
                                        @if ($umkm->excerpt)
                                            <p class="mt-0.5 line-clamp-1 text-xs text-muted-foreground">{{ $umkm->excerpt }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="kt-badge kt-badge-sm kt-badge-primary kt-badge-outline">
                                            {{ $umkm->categoryLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-muted-foreground">{{ $umkm->owner_name }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('desa.umkm.toggle', $umkm) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="kt-badge kt-badge-sm {{ $umkm->is_published ? 'kt-badge-success' : 'kt-badge-secondary' }} kt-badge-outline cursor-pointer">
                                                {{ $umkm->is_published ? 'Publikasi' : 'Draft' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        @if ($umkm->is_featured)
                                            <span class="kt-badge kt-badge-sm kt-badge-warning kt-badge-outline">Unggulan</span>
                                        @else
                                            <span class="text-muted-foreground">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="flex justify-end gap-1">
                                            @if ($umkm->is_published)
                                                <a href="{{ route('umkm.show', $umkm->slug) }}" target="_blank"
                                                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" title="Lihat">
                                                    <i class="ki-filled ki-eye"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('desa.umkm.edit', $umkm) }}" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" wire:navigate title="Edit">
                                                <i class="ki-filled ki-notepad-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('desa.umkm.destroy', $umkm) }}" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus UMKM ini?')">
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
                                        Belum ada data UMKM.
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

    @script
    <script>
        $wire.on('umkm-filter-changed', () => {
            setTimeout(() => {
                if (window.MetronicCore?.reinitDatatables) {
                    window.MetronicCore.reinitDatatables();
                }
            }, 50);
        });
    </script>
    @endscript
</div>
