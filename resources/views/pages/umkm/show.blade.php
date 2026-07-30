@extends('layouts.public')

@section('title', $umkm->name . ' — UMKM ' . $identity->name)

@section('content')
<section class="py-14 lg:py-16">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-3 lg:gap-10">
            <article class="overflow-hidden rounded-2xl bg-white p-6 shadow-sm lg:col-span-2 lg:p-10">
                <div class="mb-4 flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-desa-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-desa-600">
                        {{ $umkm->categoryLabel() }}
                    </span>
                    @if ($umkm->is_featured)
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">UMKM Unggulan</span>
                    @endif
                </div>

                <h1 class="text-2xl font-bold leading-tight text-gray-900 lg:text-3xl">{{ $umkm->name }}</h1>

                <p class="mt-3 flex items-center gap-1.5 text-sm text-gray-500">
                    <i class="ki-filled ki-user text-desa-500"></i>
                    Pemilik: {{ $umkm->owner_name }}
                </p>

                <img src="{{ $umkm->coverUrl() }}" alt="{{ $umkm->name }}"
                    class="mt-6 aspect-[16/9] w-full rounded-xl object-cover" />

                <x-share-buttons
                    :title="$umkm->name"
                    :text="$umkm->excerpt ?? ('UMKM '.$umkm->name.' — '.$umkm->owner_name)"
                    class="mt-6"
                />

                @if ($umkm->products)
                    <div class="mt-6 rounded-xl border border-desa-100 bg-desa-50/50 p-5">
                        <h2 class="mb-2 text-sm font-bold uppercase tracking-wide text-desa-600">Produk / Layanan</h2>
                        <p class="text-sm leading-relaxed text-gray-700">{{ $umkm->products }}</p>
                    </div>
                @endif

                <div class="prose prose-lg mt-6 max-w-none text-justify text-base text-gray-700 prose-headings:text-gray-900 prose-a:text-desa-600">
                    {!! $umkm->description !!}
                </div>

                <div class="mt-8 grid gap-4 rounded-xl border border-gray-100 bg-gray-50 p-5 sm:grid-cols-2">
                    @if ($umkm->address)
                        <div class="flex items-start gap-3">
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-desa-50 text-desa-600">
                                <i class="ki-filled ki-geolocation"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500">Alamat</p>
                                <p class="text-sm text-gray-800">{{ $umkm->address }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($umkm->phone)
                        <div class="flex items-start gap-3">
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-desa-50 text-desa-600">
                                <i class="ki-filled ki-phone"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500">Telepon</p>
                                <a href="tel:{{ $umkm->phone }}" class="text-sm font-medium text-desa-600 hover:underline">{{ $umkm->phone }}</a>
                            </div>
                        </div>
                    @endif
                    @if ($umkm->whatsappUrl())
                        <div class="flex items-start gap-3 sm:col-span-2">
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-green-50 text-green-600">
                                <i class="ki-filled ki-whatsapp"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500">WhatsApp</p>
                                <a href="{{ $umkm->whatsappUrl() }}" target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-green-700 hover:underline">
                                    Hubungi via WhatsApp
                                    <i class="ki-filled ki-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </article>

            <aside class="lg:col-span-1">
                <div class="sticky top-24 rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="mb-5 text-lg font-bold text-gray-900">UMKM Lainnya</h2>

                    <div class="space-y-5">
                        @forelse ($relatedUmkms as $related)
                            <a href="{{ route('umkm.show', $related->slug) }}"
                                class="group flex gap-4 transition hover:opacity-80">
                                <img src="{{ $related->coverUrl() }}" alt="{{ $related->name }}"
                                    class="size-20 shrink-0 rounded-lg object-cover" />
                                <div class="min-w-0">
                                    <p class="mb-1 text-xs font-bold uppercase tracking-wide text-desa-600">
                                        {{ $related->categoryLabel() }}
                                    </p>
                                    <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-gray-900 group-hover:text-desa-600">
                                        {{ $related->name }}
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">{{ $related->owner_name }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada UMKM terkait.</p>
                        @endforelse
                    </div>

                    <a href="{{ route('umkm.index') }}"
                        class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:border-desa-200 hover:text-desa-600">
                        Lihat Semua UMKM
                        <i class="ki-filled ki-arrow-right text-xs"></i>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
