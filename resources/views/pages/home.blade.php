@extends('layouts.public')

@section('title', $identity->name . ' — ' . $identity->locationLabel())

@section('content')
    {{-- Hero --}}
    <section id="beranda" class="relative min-h-[520px] overflow-hidden lg:min-h-[580px]">
        <img src="{{ $identity->heroImageUrl() }}" alt="Pemandangan {{ $identity->name }}"
            class="absolute inset-0 size-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/70 to-transparent"></div>
        <div class="relative mx-auto flex max-w-7xl items-center px-4 py-20 lg:px-8 lg:py-28">
            <div class="max-w-xl">
                <p class="mb-3 text-sm font-semibold uppercase tracking-wide text-desa-600">{{ $identity->welcome_text }}</p>
                <h1 class="mb-4 text-4xl font-bold leading-tight text-gray-900 lg:text-5xl">{{ $identity->name }}</h1>
                <p class="mb-8 text-base leading-relaxed text-gray-600 lg:text-lg">{{ $identity->tagline }}</p>
                <a href="#profil"
                    class="inline-flex items-center gap-2 rounded-lg bg-desa-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-desa-700">
                    Selengkapnya
                    <i class="ki-filled ki-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Stats Bar --}}
    <section class="relative z-10 -mt-16 px-4 lg:-mt-20 lg:px-8">
        <div class="mx-auto max-w-5xl rounded-2xl bg-white px-6 py-8 shadow-xl lg:px-12">
            <div @class([
                'grid gap-6 lg:gap-8',
                'grid-cols-2 lg:grid-cols-4' => $statistics->count() >= 4 || $statistics->isEmpty(),
                'grid-cols-2 lg:grid-cols-3' => $statistics->count() === 3,
                'grid-cols-2' => $statistics->count() === 2,
                'grid-cols-1' => $statistics->count() === 1,
            ])>
                @forelse ($statistics as $statistic)
                    <div class="flex items-center gap-4">
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-desa-50 text-desa-600">
                            <i class="ki-filled ki-chart text-base"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ $statistic->homeLabel() }}</p>
                            <p class="text-lg font-bold text-gray-900">{{ $statistic->displayValue() }}</p>
                        </div>
                    </div>
                @empty
                    @foreach ([
                        ['icon' => 'ki-people', 'label' => 'Penduduk', 'value' => $identity->population],
                        ['icon' => 'ki-home-2', 'label' => 'Kepala Keluarga', 'value' => $identity->households],
                        ['icon' => 'ki-map', 'label' => 'Luas Wilayah', 'value' => $identity->area],
                        ['icon' => 'ki-geolocation', 'label' => 'Dusun', 'value' => $identity->hamlets],
                    ] as $stat)
                        <div class="flex items-center gap-4">
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-desa-50 text-desa-600">
                                <i class="ki-filled {{ $stat['icon'] }} text-base"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
                                <p class="text-lg font-bold text-gray-900">{{ $stat['value'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- Tentang Kami --}}
    <section id="profil" class="mx-auto max-w-7xl px-4 py-20 lg:px-8 lg:py-28">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div>
                <p class="mb-3 text-sm font-semibold uppercase tracking-wide text-desa-600">{{ $identity->about_label }}</p>
                <h2 class="mb-5 text-3xl font-bold leading-tight text-gray-900 lg:text-4xl">{{ $identity->about_title }}</h2>
                <p class="mb-6 leading-relaxed text-gray-600">{{ $identity->about_description }}</p>
                <a href="#profil" class="inline-flex items-center gap-2 text-sm font-semibold text-desa-600 transition hover:text-desa-700">
                    Selengkapnya tentang Desa
                    <i class="ki-filled ki-arrow-right text-xs"></i>
                </a>
            </div>
            <div id="galeri" class="relative overflow-hidden rounded-2xl shadow-lg">
                <img src="{{ $identity->aboutImageUrl() }}" alt="Pemandangan {{ $identity->name }}"
                    class="aspect-[4/3] w-full object-cover" />
                <button type="button"
                    class="absolute left-1/2 top-1/2 flex size-16 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-desa-600 shadow-lg transition hover:bg-white"
                    aria-label="Putar video">
                    <i class="ki-filled ki-to-right text-2xl"></i>
                </button>
            </div>
        </div>
    </section>

    {{-- Informasi Terkini --}}
    <section id="informasi" class="bg-gray-50 ">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-desa-600">Informasi Terkini</p>
                    <h2 class="text-2xl font-bold text-gray-900 lg:text-3xl">Berita & Pengumuman Desa</h2>
                </div>
                <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-desa-600 transition hover:text-desa-700">
                    Lihat Semua Berita
                    <i class="ki-filled ki-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($latestPosts as $post)
                    <article class="overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
                        <a href="{{ route('berita.show', $post->slug) }}">
                            <img src="{{ $post->coverUrl() }}" alt="{{ $post->title }}"
                                class="aspect-[16/10] w-full object-cover" />
                        </a>
                        <div class="p-6">
                            <span class="mb-3 inline-block text-xs font-bold uppercase tracking-wide text-desa-600">
                                {{ $post->category ?? $post->typeLabel() }}
                            </span>
                            <h3 class="mb-3 text-base font-bold leading-snug text-gray-900">
                                <a href="{{ route('berita.show', $post->slug) }}" class="hover:text-desa-600">{{ $post->title }}</a>
                            </h3>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-500">
                                <span class="flex items-center gap-1.5">
                                    <i class="ki-filled ki-calendar text-desa-500"></i>
                                    {{ $post->formattedDate() }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="ki-filled ki-user text-desa-500"></i>
                                    {{ $post->authorDisplayName() }}
                                </span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl bg-white p-10 text-center text-gray-500">
                        Belum ada berita atau pengumuman.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- UMKM Desa --}}
    <section id="umkm" class="mx-auto max-w-7xl px-4 py-20 lg:px-8 lg:py-28">
        <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-desa-600">Ekonomi Desa</p>
                <h2 class="text-2xl font-bold text-gray-900 lg:text-3xl">UMKM Unggulan</h2>
            </div>
            <a href="{{ route('umkm.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-desa-600 transition hover:text-desa-700">
                Lihat Semua UMKM
                <i class="ki-filled ki-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($featuredUmkms as $umkm)
                <article class="overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
                    <a href="{{ route('umkm.show', $umkm->slug) }}">
                        <img src="{{ $umkm->coverUrl() }}" alt="{{ $umkm->name }}"
                            class="aspect-[16/10] w-full object-cover" />
                    </a>
                    <div class="p-6">
                        <span class="mb-3 inline-block text-xs font-bold uppercase tracking-wide text-desa-600">
                            {{ $umkm->categoryLabel() }}
                        </span>
                        <h3 class="mb-2 text-base font-bold leading-snug text-gray-900">
                            <a href="{{ route('umkm.show', $umkm->slug) }}" class="hover:text-desa-600">{{ $umkm->name }}</a>
                        </h3>
                        <p class="mb-2 text-sm text-gray-500">{{ $umkm->owner_name }}</p>
                        @if ($umkm->excerpt)
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $umkm->excerpt }}</p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-10 text-center text-gray-500">
                    Belum ada UMKM unggulan.
                </div>
            @endforelse
        </div>
    </section>

    {{-- Layanan Desa --}}
    <section id="layanan" class="mx-auto max-w-7xl px-4 py-20 lg:px-8 lg:py-28">
        <p class="mb-2 text-center text-sm font-semibold uppercase tracking-wide text-desa-600 lg:text-left">Layanan Desa</p>
        <h2 class="mb-10 text-center text-2xl font-bold text-gray-900 lg:text-left lg:text-3xl">Layanan Publik {{ $identity->name }}</h2>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($featuredServices as $service)
                <a href="{{ route('layanan.show', $service->slug) }}"
                    class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-desa-200 hover:shadow-md">
                    <div class="mb-4 flex size-12 items-center justify-center rounded-xl bg-desa-50 text-desa-600">
                        <i class="ki-filled {{ $service->iconClass() }} text-lg"></i>
                    </div>
                    <h3 class="mb-2 flex items-center gap-1 text-base font-bold text-gray-900">
                        {{ $service->title }}
                        <i class="ki-filled ki-arrow-right text-xs text-desa-500"></i>
                    </h3>
                    <p class="text-sm leading-relaxed text-gray-500 line-clamp-3">
                        {{ $service->excerpt ?? 'Lihat persyaratan dan prosedur layanan ini.' }}
                    </p>
                </a>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-10 text-center text-gray-500">
                    Belum ada layanan unggulan.
                </div>
            @endforelse
        </div>

        <div class="mt-8 text-center lg:text-left">
            <a href="{{ route('layanan.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-desa-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-desa-700">
                Lihat Semua Layanan
                <i class="ki-filled ki-arrow-right text-xs"></i>
            </a>
        </div>
    </section>
@endsection
