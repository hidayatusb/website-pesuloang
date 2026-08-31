@php
    $identity = $identity ?? \App\Models\VillageIdentity::current();
    $homeUrl = route('home');
    $nav = fn (string $hash) => request()->routeIs('home') ? $hash : $homeUrl.$hash;
    $isHome = request()->routeIs('home');
    $isBerita = request()->routeIs('berita.*');
    $isStatistika = request()->routeIs('statistika.*');
    $isUmkm = request()->routeIs('umkm.*');
    $isLayanan = request()->routeIs('layanan.*');
    $isInfografis = request()->routeIs('infografis.*');
    $isDokumen = request()->routeIs('dokumen.*');
    $isGaleri = request()->routeIs('galeri.*');
@endphp

<header class="sticky top-0 z-50 bg-white shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 lg:px-8">
        <a href="{{ $nav('#beranda') }}" class="flex shrink-0 items-center gap-3">
            <x-village-logo :identity="$identity" />
            <div>
                <p class="text-base font-bold leading-tight text-gray-900">{{ $identity->name }}</p>
                <p class="text-xs text-gray-500">{{ $identity->locationLabel() }}</p>
            </div>
        </a>

        <nav class="hidden items-center gap-8 lg:flex">
            <a href="{{ $nav('#beranda') }}"
                class="text-sm font-medium transition {{ $isHome ? 'border-b-2 border-desa-600 pb-0.5 text-desa-600' : 'text-gray-600 hover:text-desa-600' }}">
                Beranda
            </a>
          
            <a href="{{ route('statistika.index') }}"
                class="text-sm font-medium transition {{ $isStatistika ? 'border-b-2 border-desa-600 pb-0.5 text-desa-600' : 'text-gray-600 hover:text-desa-600' }}">
                Statistika
            </a>
            <a href="{{ route('umkm.index') }}"
                class="text-sm font-medium transition {{ $isUmkm ? 'border-b-2 border-desa-600 pb-0.5 text-desa-600' : 'text-gray-600 hover:text-desa-600' }}">
                UMKM
            </a>
            <a href="{{ route('layanan.index') }}"
                class="text-sm font-medium transition {{ $isLayanan ? 'border-b-2 border-desa-600 pb-0.5 text-desa-600' : 'text-gray-600 hover:text-desa-600' }}">
                Layanan
            </a>
            <a href="{{ route('infografis.index') }}"
                class="text-sm font-medium transition {{ $isInfografis ? 'border-b-2 border-desa-600 pb-0.5 text-desa-600' : 'text-gray-600 hover:text-desa-600' }}">
                Infografis
            </a>
            <a href="{{ route('dokumen.index') }}"
                class="text-sm font-medium transition {{ $isDokumen ? 'border-b-2 border-desa-600 pb-0.5 text-desa-600' : 'text-gray-600 hover:text-desa-600' }}">
                Dokumen
            </a>
            <a href="{{ route('galeri.index') }}"
                class="text-sm font-medium transition {{ $isGaleri ? 'border-b-2 border-desa-600 pb-0.5 text-desa-600' : 'text-gray-600 hover:text-desa-600' }}">
                Galeri
            </a>
           
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" title="Masuk" aria-label="Masuk"
                class="hidden size-10 items-center justify-center rounded-lg bg-desa-600 text-white transition hover:bg-desa-700 sm:inline-flex">
                <i class="ki-filled ki-key text-lg"></i>
            </a>
            <button type="button" onclick="toggleMobileMenu()"
                class="inline-flex size-10 items-center justify-center rounded-lg border border-gray-200 text-gray-600 lg:hidden"
                aria-label="Menu">
                <i id="menu-icon" class="ki-filled ki-menu text-lg"></i>
            </button>
        </div>
    </div>

    <nav id="mobile-menu" class="hidden border-t border-gray-100 bg-white px-4 py-4 lg:hidden">
        <div class="flex flex-col gap-1">
            <a href="{{ $nav('#beranda') }}"
                class="rounded-lg px-3 py-2 text-sm font-medium {{ $isHome ? 'bg-desa-50 text-desa-600' : 'text-gray-600 hover:bg-gray-50' }}">
                Beranda
            </a>
         
            <a href="{{ route('statistika.index') }}"
                class="rounded-lg px-3 py-2 text-sm {{ $isStatistika ? 'bg-desa-50 font-medium text-desa-600' : 'text-gray-600 hover:bg-gray-50' }}">
                Statistika
            </a>
            <a href="{{ route('umkm.index') }}"
                class="rounded-lg px-3 py-2 text-sm {{ $isUmkm ? 'bg-desa-50 font-medium text-desa-600' : 'text-gray-600 hover:bg-gray-50' }}">
                UMKM
            </a>
            <a href="{{ route('layanan.index') }}"
                class="rounded-lg px-3 py-2 text-sm {{ $isLayanan ? 'bg-desa-50 font-medium text-desa-600' : 'text-gray-600 hover:bg-gray-50' }}">
                Layanan
            </a>
            <a href="{{ route('infografis.index') }}"
                class="rounded-lg px-3 py-2 text-sm {{ $isInfografis ? 'bg-desa-50 font-medium text-desa-600' : 'text-gray-600 hover:bg-gray-50' }}">
                Infografis
            </a>
            <a href="{{ route('dokumen.index') }}"
                class="rounded-lg px-3 py-2 text-sm {{ $isDokumen ? 'bg-desa-50 font-medium text-desa-600' : 'text-gray-600 hover:bg-gray-50' }}">
                Dokumen
            </a>
            <a href="{{ route('galeri.index') }}"
                class="rounded-lg px-3 py-2 text-sm {{ $isGaleri ? 'bg-desa-50 font-medium text-desa-600' : 'text-gray-600 hover:bg-gray-50' }}">
                Galeri
            </a>
            <a href="{{ $nav('#kontak') }}" class="rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">Kontak</a>
            <a href="{{ route('login') }}"
                class="mt-2 flex items-center justify-center gap-2 rounded-lg bg-desa-600 px-4 py-2.5 text-sm font-medium text-white">
                <i class="ki-filled ki-key text-lg"></i>
                Masuk
            </a>
        </div>
    </nav>
</header>
