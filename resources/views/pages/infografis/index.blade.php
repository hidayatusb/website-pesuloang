@extends('layouts.public')

@section('title', 'Infografis — ' . $identity->name)

@section('content')
<section class="py-14 lg:py-16">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="mb-10">
            <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-desa-600">Publikasi Visual</p>
            <h1 class="text-2xl font-bold text-gray-900 lg:text-3xl">Infografis {{ $identity->name }}</h1>
            <p class="mt-2 text-gray-600">Informasi dan data desa dalam bentuk visual yang mudah dipahami.</p>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($infographics as $infographic)
                <article class="group overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
                    <button type="button"
                        class="block w-full cursor-zoom-in overflow-hidden bg-gray-100"
                        onclick="openInfographicLightbox(@js($infographic->imageUrl()), @js($infographic->title))">
                        <img src="{{ $infographic->imageUrl() }}" alt="{{ $infographic->title }}" loading="lazy"
                            class="aspect-[4/5] w-full object-cover transition duration-300 group-hover:scale-105" />
                    </button>
                    <div class="p-5">
                        <p class="mb-1.5 text-xs font-medium text-gray-400">
                            <i class="ki-filled ki-calendar text-desa-500"></i>
                            {{ $infographic->formattedDate() }}
                        </p>
                        <h2 class="text-base font-bold leading-snug text-gray-900">{{ $infographic->title }}</h2>
                        @if ($infographic->description)
                            <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $infographic->description }}</p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-12 text-center text-gray-500">
                    Belum ada infografis yang dipublikasikan.
                </div>
            @endforelse
        </div>

        @if ($infographics->hasPages())
            <div class="mt-10">
                {{ $infographics->links() }}
            </div>
        @endif
    </div>
</section>

<div id="infographic-lightbox" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 p-4"
    onclick="closeInfographicLightbox()">
    <div class="relative max-h-full max-w-4xl" onclick="event.stopPropagation()">
        <button type="button" onclick="closeInfographicLightbox()"
            class="absolute -top-3 -right-3 z-10 inline-flex size-9 items-center justify-center rounded-full bg-white text-gray-700 shadow-lg hover:text-desa-600"
            aria-label="Tutup">
            <i class="ki-filled ki-cross text-lg"></i>
        </button>
        <img id="infographic-lightbox-image" src="" alt=""
            class="max-h-[85vh] w-auto rounded-xl object-contain shadow-2xl" />
        <p id="infographic-lightbox-title" class="mt-3 text-center text-sm font-medium text-white"></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openInfographicLightbox(src, title) {
        const lightbox = document.getElementById('infographic-lightbox');
        document.getElementById('infographic-lightbox-image').src = src;
        document.getElementById('infographic-lightbox-image').alt = title;
        document.getElementById('infographic-lightbox-title').textContent = title;
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeInfographicLightbox() {
        const lightbox = document.getElementById('infographic-lightbox');
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeInfographicLightbox();
    });
</script>
@endpush
