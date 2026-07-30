@extends('layouts.public')

@section('title', $service->title . ' — Layanan ' . $identity->name)

@section('content')
<section class="py-14 lg:py-16">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-3 lg:gap-10">
            <article class="overflow-hidden rounded-2xl bg-white p-6 shadow-sm lg:col-span-2 lg:p-10">
                <div class="mb-4 flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-desa-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-desa-600">
                        {{ $service->categoryLabel() }}
                    </span>
                    @if ($service->is_featured)
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">Layanan Unggulan</span>
                    @endif
                </div>

                <div class="flex items-start gap-4">
                    <div class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-desa-50 text-desa-600">
                        <i class="ki-filled {{ $service->iconClass() }} text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold leading-tight text-gray-900 lg:text-3xl">{{ $service->title }}</h1>
                        @if ($service->excerpt)
                            <p class="mt-2 text-sm text-gray-500">{{ $service->excerpt }}</p>
                        @endif
                    </div>
                </div>

                @if ($service->image_path)
                    <img src="{{ $service->coverUrl() }}" alt="{{ $service->title }}"
                        class="mt-6 aspect-[16/9] w-full rounded-xl object-cover" />
                @endif

                <x-share-buttons
                    :title="$service->title"
                    :text="$service->excerpt ?? ('Layanan '.$service->title)"
                    class="mt-6"
                />

                @if ($service->description)
                    <div class="prose prose-lg mt-6 max-w-none text-justify text-base text-gray-700 prose-headings:text-gray-900 prose-a:text-desa-600">
                        {!! $service->description !!}
                    </div>
                @endif

                <div class="mt-8 rounded-2xl border border-amber-100 bg-amber-50/40 p-6 lg:p-8">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                            <i class="ki-filled ki-verify"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Persyaratan</h2>
                    </div>
                    <div class="prose prose-sm max-w-none text-gray-700 prose-li:my-1 prose-ul:my-2 prose-ol:my-2">
                        {!! $service->requirements !!}
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-desa-100 bg-desa-50/40 p-6 lg:p-8">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-full bg-desa-100 text-desa-700">
                            <i class="ki-filled ki-route"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Prosedur</h2>
                    </div>
                    <div class="prose prose-sm max-w-none text-gray-700 prose-li:my-1 prose-ul:my-2 prose-ol:my-2">
                        {!! $service->procedures !!}
                    </div>
                </div>
            </article>

            <aside class="lg:col-span-1">
                <div class="sticky top-24 rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="mb-5 text-lg font-bold text-gray-900">Layanan Lainnya</h2>

                    <div class="space-y-5">
                        @forelse ($relatedServices as $related)
                            <a href="{{ route('layanan.show', $related->slug) }}"
                                class="group flex gap-4 transition hover:opacity-80">
                                <div class="flex size-14 shrink-0 items-center justify-center rounded-lg bg-desa-50 text-desa-600">
                                    <i class="ki-filled {{ $related->iconClass() }}"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="mb-1 text-xs font-bold uppercase tracking-wide text-desa-600">
                                        {{ $related->categoryLabel() }}
                                    </p>
                                    <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-gray-900 group-hover:text-desa-600">
                                        {{ $related->title }}
                                    </h3>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada layanan terkait.</p>
                        @endforelse
                    </div>

                    <a href="{{ route('layanan.index') }}"
                        class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:border-desa-200 hover:text-desa-600">
                        Lihat Semua Layanan
                        <i class="ki-filled ki-arrow-right text-xs"></i>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
