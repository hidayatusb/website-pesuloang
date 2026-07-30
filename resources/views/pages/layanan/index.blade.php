@extends('layouts.public')

@section('title', 'Layanan Desa — ' . $identity->name)

@section('content')
<section class="py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="mb-10">
            <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-desa-600">Pelayanan Publik</p>
            <h1 class="text-2xl font-bold text-gray-900 lg:text-3xl">Layanan {{ $identity->name }}</h1>
            <p class="mt-2 text-gray-600">Informasi layanan desa beserta persyaratan dan prosedur pengajuan</p>
        </div>

        <div class="mb-8 flex flex-wrap gap-2">
            <a href="{{ route('layanan.index') }}"
                class="rounded-lg px-4 py-2 text-sm font-medium {{ ! $activeCategory ? 'bg-desa-600 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:ring-desa-200' }}">
                Semua
            </a>
            @foreach ($categories as $value => $label)
                <a href="{{ route('layanan.index', ['kategori' => $value]) }}"
                    class="rounded-lg px-4 py-2 text-sm font-medium {{ $activeCategory === $value ? 'bg-desa-600 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:ring-desa-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($services as $service)
                <article class="overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-desa-200 hover:shadow-md">
                    <div class="mb-4 flex size-12 items-center justify-center rounded-xl bg-desa-50 text-desa-600">
                        <i class="ki-filled {{ $service->iconClass() }} text-lg"></i>
                    </div>
                    <div class="mb-3 flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wide text-desa-600">
                            {{ $service->categoryLabel() }}
                        </span>
                        @if ($service->is_featured)
                            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">Unggulan</span>
                        @endif
                    </div>
                    <h2 class="mb-2 text-base font-bold leading-snug text-gray-900">
                        <a href="{{ route('layanan.show', $service->slug) }}" class="hover:text-desa-600">{{ $service->title }}</a>
                    </h2>
                    @if ($service->excerpt)
                        <p class="mb-4 text-sm leading-relaxed text-gray-500 line-clamp-3">{{ $service->excerpt }}</p>
                    @endif
                    <a href="{{ route('layanan.show', $service->slug) }}"
                        class="inline-flex items-center gap-1 text-sm font-semibold text-desa-600 hover:underline">
                        Lihat persyaratan & prosedur
                        <i class="ki-filled ki-arrow-right text-xs"></i>
                    </a>
                </article>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-12 text-center text-gray-500">
                    Belum ada layanan yang dipublikasikan.
                </div>
            @endforelse
        </div>

        @if ($services->hasPages())
            <div class="mt-10">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
