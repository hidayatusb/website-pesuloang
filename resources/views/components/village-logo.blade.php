@props(['identity', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'size-10',
        'md' => 'size-11',
        'lg' => 'size-14',
    ];
    $iconSizes = [
        'sm' => 'size-6',
        'md' => 'size-7',
        'lg' => 'size-9',
    ];
    $boxClass = $sizes[$size] ?? $sizes['md'];
    $iconClass = $iconSizes[$size] ?? $iconSizes['md'];
@endphp

<div {{ $attributes->merge(['class' => "flex {$boxClass} shrink-0 items-center justify-center overflow-hidden rounded-full bg-desa-600 text-white"]) }}>
    @if ($identity->logoUrl())
        <img src="{{ $identity->logoUrl() }}" alt="Logo {{ $identity->name }}" class="size-full object-cover" />
    @else
        <svg class="{{ $iconClass }}" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 3L2 12h3v8h6v-5h2v5h6v-8h3L12 3zm0 2.8L18 13h-2v6h-2v-5H10v5H8v-6H6l6-7.2z"/>
        </svg>
    @endif
</div>
