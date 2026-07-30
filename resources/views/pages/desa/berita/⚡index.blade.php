<?php

use App\Models\VillagePost;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::demo1.base')] class extends Component {
    public string $filter = 'semua';

    public function updatingFilter(): void
    {
        $this->dispatch('berita-filter-changed');
    }

    public function with(): array
    {
        $posts = VillagePost::query()
            ->with('author')
            ->when($this->filter !== 'semua', fn ($q) => $q->where('type', $this->filter))
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return ['posts' => $posts];
    }
};
?>

<div>
    <div class="kt-container-fixed">
        <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5 lg:items-end">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-mono">Berita & Pengumuman</h1>
                <p class="text-sm font-normal text-secondary-foreground">
                    Kelola berita dan pengumuman yang tampil di website desa
                </p>
            </div>
            <a href="{{ route('desa.berita.create') }}" class="kt-btn kt-btn-primary" wire:navigate>
                <i class="ki-filled ki-plus"></i>
                Tambah Konten
            </a>
        </div>
    </div>

    <div class="kt-container-fixed">
        <div class="kt-card">
            <div class="kt-card-header flex-wrap gap-3">
                <div class="flex flex-wrap gap-2 mt-3">
                    @foreach (['semua' => 'Semua', 'berita' => 'Berita', 'pengumuman' => 'Pengumuman'] as $value => $label)
                        <button type="button" wire:click="$set('filter', '{{ $value }}')"
                            class="kt-btn kt-btn-sm {{ $filter === $value ? 'kt-btn-primary' : 'kt-btn-outline' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                <input type="text" placeholder="Cari judul..." class="kt-input sm:w-48 mb-3"
                    data-kt-datatable-search="#berita_datatable" />
            </div>

            <div id="berita_datatable" class="kt-card-table" data-kt-datatable="true" data-kt-datatable-page-size="10"
                wire:key="berita-datatable-{{ $filter }}">
                <div class="kt-table-wrapper kt-scrollable max-w-full overflow-auto">
                    <table class="kt-table kt-table-border min-w-[960px]" data-kt-datatable-table="true">
                        <thead>
                            <tr>
                                <th scope="col" class="min-w-[280px]" data-kt-datatable-column="title">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Judul</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[100px]" data-kt-datatable-column="type">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Tipe</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[120px]" data-kt-datatable-column="category">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Kategori</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[140px]" data-kt-datatable-column="author">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Penulis</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[100px]" data-kt-datatable-column="status">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Status</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="min-w-[120px]" data-kt-datatable-column="date">
                                    <span class="kt-table-col">
                                        <span class="kt-table-col-label">Tanggal</span>
                                        <span class="kt-table-col-sort"></span>
                                    </span>
                                </th>
                                <th scope="col" class="text-end" data-kt-datatable-column="actions"
                                    data-kt-datatable-column-sort="false"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr wire:key="post-{{ $post->id }}">
                                    <td>
                                        <div class="flex items-center gap-3">
                                          
                                            <p class="min-w-0 font-medium text-foreground">{{ $post->title }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="kt-badge kt-badge-sm {{ $post->type === 'pengumuman' ? 'kt-badge-warning' : 'kt-badge-primary' }} kt-badge-outline">
                                            {{ $post->typeLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $post->category ?? '-' }}</td>
                                    <td class="text-muted-foreground">{{ $post->authorDisplayName() }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('desa.berita.toggle', $post) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="kt-badge kt-badge-sm {{ $post->is_published ? 'kt-badge-success' : 'kt-badge-secondary' }} kt-badge-outline cursor-pointer">
                                                {{ $post->is_published ? 'Publikasi' : 'Draft' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-muted-foreground">{{ $post->formattedDate() }}</td>
                                    <td class="text-end">
                                        <div class="flex justify-start gap-1">
                                            @if ($post->is_published)
                                                <a href="{{ route('berita.show', $post->slug) }}" target="_blank"
                                                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" title="Lihat">
                                                    <i class="ki-filled ki-eye"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('desa.berita.edit', $post) }}" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost" wire:navigate title="Edit">
                                                <i class="ki-filled ki-notepad-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('desa.berita.destroy', $post) }}" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
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
                                    <td colspan="7" class="py-10 text-center text-muted-foreground">
                                        Belum ada berita atau pengumuman.
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
        $wire.on('berita-filter-changed', () => {
            setTimeout(() => {
                if (window.MetronicCore?.reinitDatatables) {
                    window.MetronicCore.reinitDatatables();
                }
            }, 50);
        });
    </script>
    @endscript
</div>
