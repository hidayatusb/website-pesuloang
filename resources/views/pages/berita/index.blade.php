@extends('layouts.public')

@section('title', 'Berita & Pengumuman — ' . $identity->name)

@section('content')
<section class="py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="mb-10">
            <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-desa-600">Informasi Terkini</p>
            <h1 class="text-2xl font-bold text-gray-900 lg:text-3xl">Berita & Pengumuman Desa</h1>
            <p class="mt-2 text-gray-600">Berita dan pengumuman resmi {{ $identity->name }}</p>
        </div>

        <div class="mb-8 flex flex-wrap gap-2">
            <a href="{{ route('berita.index') }}"
                class="rounded-lg px-4 py-2 text-sm font-medium {{ ! $activeType ? 'bg-desa-600 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:ring-desa-200' }}">
                Semua
            </a>
            <a href="{{ route('berita.index', ['tipe' => 'berita']) }}"
                class="rounded-lg px-4 py-2 text-sm font-medium {{ $activeType === 'berita' ? 'bg-desa-600 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:ring-desa-200' }}">
                Berita
            </a>
            <a href="{{ route('berita.index', ['tipe' => 'pengumuman']) }}"
                class="rounded-lg px-4 py-2 text-sm font-medium {{ $activeType === 'pengumuman' ? 'bg-desa-600 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:ring-desa-200' }}">
                Pengumuman
            </a>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <article class="overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
                    <a href="{{ route('berita.show', $post->slug) }}">
                        <img src="{{ $post->coverUrl() }}" alt="{{ $post->title }}"
                            class="aspect-[16/10] w-full object-cover" />
                    </a>
                    <div class="p-6">
                        <div class="mb-3 flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wide text-desa-600">
                                {{ $post->category ?? $post->typeLabel() }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $post->typeLabel() }}</span>
                        </div>
                        <h2 class="mb-3 text-base font-bold leading-snug text-gray-900">
                            <a href="{{ route('berita.show', $post->slug) }}" class="hover:text-desa-600">{{ $post->title }}</a>
                        </h2>
                        @if ($post->excerpt)
                            <p class="mb-3 text-sm text-gray-500 line-clamp-2">{{ $post->excerpt }}</p>
                        @endif
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
                <div class="col-span-full rounded-2xl bg-white p-12 text-center text-gray-500">
                    Belum ada konten yang dipublikasikan.
                </div>
            @endforelse
        </div>

        @if ($posts->hasPages())
            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
