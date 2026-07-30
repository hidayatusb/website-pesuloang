@extends('layouts.public')

@section('title', $post->title . ' — ' . $identity->name)

@section('content')
<section class="py-8 lg:py-8">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        

        <div class="grid gap-8 lg:grid-cols-3 lg:gap-5">
            <article class="overflow-hidden rounded-2xl bg-white p-6 shadow-sm lg:col-span-2 lg:p-10">
                <h1 class="text-2xl font-bold leading-tight text-gray-900 lg:text-3xl">{{ $post->title }}</h1>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span @class([
                        'rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide',
                        'bg-amber-50 text-amber-700' => $post->type === 'pengumuman',
                        'bg-desa-50 text-desa-600' => $post->type !== 'pengumuman',
                    ])>
                        {{ $post->category ?? $post->typeLabel() }}
                    </span>
                    <span @class([
                        'rounded-full px-3 py-1 text-xs font-medium',
                        'bg-amber-50/60 text-amber-600' => $post->type === 'pengumuman',
                        'bg-gray-100 text-gray-600' => $post->type !== 'pengumuman',
                    ])>
                        {{ $post->typeLabel() }}
                    </span>
                    <span class="flex items-center gap-1.5 text-sm text-gray-500">
                        <i class="ki-filled ki-calendar text-desa-500"></i>
                        {{ $post->formattedDate() }}
                    </span>
                    <span class="text-gray-300">|</span>
                    <span class="flex items-center gap-1.5 text-sm text-gray-500">
                        <i class="ki-filled ki-user text-desa-500"></i>
                        {{ $post->authorDisplayName() }}
                    </span>
                </div>

                <img src="{{ $post->coverUrl() }}" alt="{{ $post->title }}"
                    class="mt-6 aspect-[16/9] w-full rounded-xl object-cover" />

                <x-share-buttons
                    :title="$post->title"
                    :text="$post->excerpt ?? $post->title"
                    class="mt-6"
                />

                <div class="prose prose-lg text-base text-justify mt-6 max-w-none text-gray-700 prose-headings:text-gray-900 prose-a:text-desa-600 prose-a:no-underline hover:prose-a:underline prose-img:rounded-xl">
                    {!! $post->content !!}
                </div>
            </article>

            <aside class="lg:col-span-1">
                <div class="sticky top-24 rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="mb-5 text-lg font-bold text-gray-900">Berita Terkait</h2>

                    <div class="space-y-5">
                        @forelse ($relatedPosts as $related)
                            <a href="{{ route('berita.show', $related->slug) }}"
                                class="group flex gap-4 transition hover:opacity-80">
                                <img src="{{ $related->coverUrl() }}" alt="{{ $related->title }}"
                                    class="size-20 shrink-0 rounded-lg object-cover" />
                                <div class="min-w-0">
                                    <p class="mb-1 text-xs font-bold uppercase tracking-wide text-desa-600">
                                        {{ $related->category ?? $related->typeLabel() }}
                                    </p>
                                    <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-gray-900 group-hover:text-desa-600">
                                        {{ $related->title }}
                                    </h3>
                                    <p class="mt-1 flex items-center gap-1 text-xs text-gray-500">
                                        <i class="ki-filled ki-calendar text-desa-500"></i>
                                        {{ $related->formattedDate() }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada berita terkait.</p>
                        @endforelse
                    </div>

                    <a href="{{ route('berita.index') }}"
                        class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:border-desa-200 hover:text-desa-600">
                        Lihat Semua Berita
                        <i class="ki-filled ki-arrow-right text-xs"></i>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
