@php
    $identity = $identity ?? \App\Models\VillageIdentity::current();
    $homeUrl = route('home');
    $nav = fn (string $hash) => request()->routeIs('home') ? $hash : $homeUrl.$hash;
@endphp

<footer id="kontak" class="border-t border-gray-200 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <div class="mb-4 flex items-center gap-3">
                    <x-village-logo :identity="$identity" size="sm" />
                    <div>
                        <p class="font-bold text-gray-900">{{ $identity->name }}</p>
                        <p class="text-xs text-gray-500">{{ $identity->kecamatan }}</p>
                    </div>
                </div>
                <p class="mb-2 text-sm text-gray-500">{{ $identity->address }}</p>
                <p class="text-sm italic text-gray-500">"{{ $identity->tagline }}"</p>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-gray-900">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="{{ $nav('#beranda') }}" class="transition hover:text-desa-600">Beranda</a></li>
                    <li><a href="{{ $nav('#profil') }}" class="transition hover:text-desa-600">Profil Desa</a></li>
                    <li><a href="{{ $nav('#informasi') }}" class="transition hover:text-desa-600">Informasi</a></li>
                    <li><a href="{{ route('statistika.index') }}" class="transition hover:text-desa-600">Statistika</a></li>
                    <li><a href="{{ route('umkm.index') }}" class="transition hover:text-desa-600">UMKM Desa</a></li>
                    <li><a href="{{ route('layanan.index') }}" class="transition hover:text-desa-600">Layanan</a></li>
                    <li><a href="{{ $nav('#galeri') }}" class="transition hover:text-desa-600">Galeri</a></li>
                    <li><a href="{{ $nav('#kontak') }}" class="transition hover:text-desa-600">Kontak</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-gray-900">Layanan</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="{{ route('layanan.show', 'surat-pengantar') }}" class="transition hover:text-desa-600">Surat Pengantar</a></li>
                    <li><a href="{{ route('layanan.show', 'surat-keterangan-domisili') }}" class="transition hover:text-desa-600">Surat Keterangan</a></li>
                    <li><a href="{{ route('layanan.show', 'permohonan-bantuan-sosial') }}" class="transition hover:text-desa-600">Permohonan</a></li>
                    <li><a href="{{ route('layanan.show', 'pengaduan-aspirasi-warga') }}" class="transition hover:text-desa-600">Pengaduan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-gray-900">Kontak Kami</h4>
                <ul class="space-y-3 text-sm text-gray-500">
                    @if ($identity->address)
                        <li class="flex items-start gap-2">
                            <i class="ki-filled ki-geolocation mt-0.5 text-desa-500"></i>
                            {{ $identity->address }}
                        </li>
                    @endif
                    @if ($identity->phone)
                        <li class="flex items-center gap-2">
                            <i class="ki-filled ki-phone text-desa-500"></i>
                            {{ $identity->phone }}
                        </li>
                    @endif
                    @if ($identity->email)
                        <li class="flex items-center gap-2">
                            <i class="ki-filled ki-sms text-desa-500"></i>
                            {{ $identity->email }}
                        </li>
                    @endif
                </ul>
                <div class="mt-5 flex gap-3">
                    @if ($identity->facebook_url)
                        <a href="{{ $identity->facebook_url }}" class="flex size-9 items-center justify-center rounded-full bg-desa-100 text-desa-600 transition hover:bg-desa-600 hover:text-white" aria-label="Facebook" target="_blank" rel="noopener">
                            <i class="ki-filled ki-facebook text-sm"></i>
                        </a>
                    @endif
                    @if ($identity->instagram_url)
                        <a href="{{ $identity->instagram_url }}" class="flex size-9 items-center justify-center rounded-full bg-desa-100 text-desa-600 transition hover:bg-desa-600 hover:text-white" aria-label="Instagram" target="_blank" rel="noopener">
                            <i class="ki-filled ki-instagram text-sm"></i>
                        </a>
                    @endif
                    @if ($identity->youtube_url)
                        <a href="{{ $identity->youtube_url }}" class="flex size-9 items-center justify-center rounded-full bg-desa-100 text-desa-600 transition hover:bg-desa-600 hover:text-white" aria-label="YouTube" target="_blank" rel="noopener">
                            <i class="ki-filled ki-youtube text-sm"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="border-t border-gray-200 py-5 text-center text-sm text-gray-400">
        &copy; {{ date('Y') }} {{ $identity->name }}. All rights reserved.
    </div>
</footer>
