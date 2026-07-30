@extends('layouts.public')

@section('title', 'UMKM Desa — ' . $identity->name)

@section('content')
<section class="py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="mb-10">
            <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-desa-600">Ekonomi Desa</p>
            <h1 class="text-2xl font-bold text-gray-900 lg:text-3xl">UMKM {{ $identity->name }}</h1>
            <p class="mt-2 text-gray-600">Dukung dan kenali usaha mikro warga desa</p>
        </div>

        <div class="mb-8 flex flex-wrap gap-2">
            <a href="{{ route('umkm.index') }}"
                class="rounded-lg px-4 py-2 text-sm font-medium {{ ! $activeCategory ? 'bg-desa-600 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:ring-desa-200' }}">
                Semua
            </a>
            @foreach ($categories as $value => $label)
                <a href="{{ route('umkm.index', ['kategori' => $value]) }}"
                    class="rounded-lg px-4 py-2 text-sm font-medium {{ $activeCategory === $value ? 'bg-desa-600 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:ring-desa-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($umkms as $umkm)
                <article class="overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
                    <a href="{{ route('umkm.show', $umkm->slug) }}">
                        <img src="{{ $umkm->coverUrl() }}" alt="{{ $umkm->name }}"
                            class="aspect-[16/10] w-full object-cover" />
                    </a>
                    <div class="p-6">
                        <div class="mb-3 flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wide text-desa-600">
                                {{ $umkm->categoryLabel() }}
                            </span>
                            @if ($umkm->is_featured)
                                <span class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">Unggulan</span>
                            @endif
                        </div>
                        <h2 class="mb-2 text-base font-bold leading-snug text-gray-900">
                            <a href="{{ route('umkm.show', $umkm->slug) }}" class="hover:text-desa-600">{{ $umkm->name }}</a>
                        </h2>
                        <p class="mb-2 text-sm text-gray-500">
                            <i class="ki-filled ki-user text-desa-500"></i>
                            {{ $umkm->owner_name }}
                        </p>
                        @if ($umkm->excerpt)
                            <p class="mb-3 text-sm text-gray-500 line-clamp-2">{{ $umkm->excerpt }}</p>
                        @endif
                        @if ($umkm->address)
                            <p class="flex items-start gap-1.5 text-sm text-gray-500">
                                <i class="ki-filled ki-geolocation mt-0.5 shrink-0 text-desa-500"></i>
                                <span class="line-clamp-1">{{ $umkm->address }}</span>
                            </p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-12 text-center text-gray-500">
                    Belum ada UMKM yang dipublikasikan.
                </div>
            @endforelse
        </div>

        @if ($umkms->hasPages())
            <div class="mt-10">
                {{ $umkms->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
