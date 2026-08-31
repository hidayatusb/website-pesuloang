<div>
    <div class="kt-container-fixed">
        <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5 lg:items-end">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-mono">
                    Dashboard
                </h1>
                <div class="flex items-center gap-2 text-sm font-normal text-secondary-foreground">
                    Selamat datang, {{ auth()->user()->name }}
                </div>
            </div>
        </div>
    </div>
    <div class="kt-container-fixed">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 lg:gap-7.5">
            @foreach ($stats as $stat)
                <a href="{{ $stat['route'] }}" wire:navigate
                    class="kt-card group transition hover:border-primary/40 hover:shadow-md">
                    <div class="kt-card-content flex items-center justify-between gap-4 p-5 lg:p-7">
                        <div class="flex flex-col gap-1.5">
                            <span class="text-3xl font-semibold text-mono">{{ number_format($stat['total'], 0, ',', '.') }}</span>
                            <span class="text-sm font-medium text-foreground">{{ $stat['label'] }}</span>
                            <span class="text-xs text-muted-foreground">
                                {{ number_format($stat['published'], 0, ',', '.') }} {{ $stat['published_label'] ?? 'dipublikasi' }}
                            </span>
                        </div>
                        <div class="flex size-14 shrink-0 items-center justify-center rounded-xl bg-accent/60 transition group-hover:bg-primary/10">
                            <i class="ki-filled {{ $stat['icon'] }} text-2xl text-primary"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
