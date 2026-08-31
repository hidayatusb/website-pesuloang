@extends('layouts.public')

@section('title', 'Dokumentasi & Galeri — ' . $identity->name)

@section('content')
<section class="py-14 lg:py-16">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="mb-10">
            <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-desa-600">Dokumentasi Kegiatan</p>
            <h1 class="text-2xl font-bold text-gray-900 lg:text-3xl">Galeri {{ $identity->name }}</h1>
            <p class="mt-2 text-gray-600">Dokumentasi foto kegiatan dan momen penting di desa.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($galleries as $gallery)
                <figure class="group overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
                    <button type="button"
                        class="block w-full cursor-zoom-in overflow-hidden bg-gray-100"
                        onclick="openGalleryLightbox(@js($gallery->imageUrl()), @js($gallery->title))">
                        <img src="{{ $gallery->imageUrl() }}" alt="{{ $gallery->title }}" loading="lazy"
                            class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-105" />
                    </button>
                    <figcaption class="p-4">
                        <h2 class="text-sm font-semibold leading-snug text-gray-900">{{ $gallery->title }}</h2>
                        <p class="mt-1 text-xs text-gray-400">
                            <i class="ki-filled ki-calendar text-desa-500"></i>
                            {{ $gallery->formattedDate() }}
                        </p>
                    </figcaption>
                </figure>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-12 text-center text-gray-500">
                    Belum ada foto dokumentasi yang dipublikasikan.
                </div>
            @endforelse
        </div>

        @if ($galleries->hasPages())
            <div class="mt-10">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>
</section>

<div id="gallery-lightbox" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 p-4"
    onclick="closeGalleryLightbox()">
    <div class="relative max-h-full max-w-5xl" onclick="event.stopPropagation()">
        <button type="button" onclick="closeGalleryLightbox()"
            class="absolute -top-3 -right-3 z-10 inline-flex size-9 items-center justify-center rounded-full bg-white text-gray-700 shadow-lg hover:text-desa-600"
            aria-label="Tutup">
            <i class="ki-filled ki-cross text-lg"></i>
        </button>
        <img id="gallery-lightbox-image" src="" alt=""
            class="max-h-[85vh] w-auto rounded-xl object-contain shadow-2xl" />
        <p id="gallery-lightbox-title" class="mt-3 text-center text-sm font-medium text-white"></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openGalleryLightbox(src, title) {
        const lightbox = document.getElementById('gallery-lightbox');
        document.getElementById('gallery-lightbox-image').src = src;
        document.getElementById('gallery-lightbox-image').alt = title;
        document.getElementById('gallery-lightbox-title').textContent = title;
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeGalleryLightbox() {
        const lightbox = document.getElementById('gallery-lightbox');
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeGalleryLightbox();
    });
</script>
@endpush
