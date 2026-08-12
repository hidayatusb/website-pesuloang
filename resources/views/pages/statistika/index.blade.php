@extends('layouts.public')

@section('title', 'Statistika Desa — ' . $identity->name)

@section('content')
<section class="py-14 lg:py-16">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="mb-8">
            <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-desa-600">Data Desa</p>
            <h1 class="text-2xl font-bold text-gray-900 lg:text-3xl">Statistika {{ $identity->name }}</h1>
            <p class="mt-2 text-gray-600">Data statistik resmi desa berdasarkan kategori indikator.</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="grid lg:grid-cols-[280px_1fr]">
                <aside class="border-b border-gray-200 bg-gray-50 lg:border-b-0 lg:border-r">
                    <div class="border-b border-gray-200 px-5 py-4">
                        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-700">Kategori Statistik</h2>
                    </div>
                    <nav class="p-3">
                        @forelse ($categories as $category)
                            <a href="{{ route('statistika.index', ['kategori' => $category->slug]) }}"
                                @class([
                                    'mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition',
                                    'bg-desa-600 text-white shadow-sm' => $activeCategory?->id === $category->id,
                                    'text-gray-700 hover:bg-white hover:text-desa-600' => $activeCategory?->id !== $category->id,
                                ])>
                                <i class="ki-filled {{ $category->iconClass() }} text-base"></i>
                                <span>{{ $category->name }}</span>
                            </a>
                        @empty
                            <p class="px-3 py-4 text-sm text-gray-500">Belum ada kategori statistik.</p>
                        @endforelse
                    </nav>
                </aside>

                <div class="p-6 lg:p-8">
                    @if ($activeCategory)
                        @php $columns = $activeCategory->columnDefinitions(); @endphp
                        <div class="mb-6 border-b border-gray-100 pb-5">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">{{ $activeCategory->name }}</h2>
                                    @if ($activeCategory->description)
                                        <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $activeCategory->description }}</p>
                                    @endif
                                </div>
                                <div class="flex shrink-0 items-center gap-2">
                                    <a href="{{ route('statistika.export', ['format' => 'pdf', 'kategori' => $activeCategory->slug]) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm transition hover:border-desa-300 hover:text-desa-600">
                                        <i class="ki-filled ki-file-down text-sm"></i>
                                        PDF
                                    </a>
                                    <a href="{{ route('statistika.export', ['format' => 'excel', 'kategori' => $activeCategory->slug]) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm transition hover:border-desa-300 hover:text-desa-600">
                                        <i class="ki-filled ki-file-down text-sm"></i>
                                        Excel
                                    </a>
                                    <a href="{{ route('statistika.export', ['format' => 'excel']) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-desa-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-desa-700"
                                        title="Unduh seluruh kategori dalam satu file Excel">
                                        <i class="ki-filled ki-folder-down text-sm"></i>
                                        Semua Data
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        @foreach ($columns as $column)
                                            <th class="px-4 py-3 text-left font-semibold text-gray-700">{{ $column['label'] }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse ($activeCategory->activeItems as $item)
                                        <tr class="hover:bg-gray-50/80">
                                            @foreach ($columns as $column)
                                                <td @class([
                                                    'px-4 py-3',
                                                    'font-medium text-gray-900' => $loop->first,
                                                    'text-gray-500' => ! $loop->first,
                                                ])>
                                                    {{ $item->valueFor($column['key']) ?: '-' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($columns) }}" class="px-4 py-10 text-center text-gray-500">
                                                Belum ada data statistik untuk kategori ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <x-statistic-chart
                            :chart-id="'public-statistic-chart-'.$activeCategory->id"
                            :title="'Grafik '.$activeCategory->name"
                            :dataset="$activeCategory->chartDataset()"
                            class="mt-6"
                        />
                    @else
                        <div class="py-16 text-center text-gray-500">
                            Pilih kategori statistik di menu sebelah kiri.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
