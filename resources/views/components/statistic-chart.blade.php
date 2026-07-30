@props([
    'chartId',
    'title' => '',
    'dataset' => [],
    'height' => 320,
    'showTypeSelector' => true,
    'defaultType' => 'bar',
    'autoInit' => true,
])

@php
    $mode = $dataset['mode'] ?? 'simple';
    $rows = collect($dataset['rows'] ?? $dataset)->filter(fn ($row) => ($row['value'] ?? null) !== null)->values();
    $showChart = $mode === 'grouped'
        ? count($dataset['categories'] ?? []) > 0 && count($dataset['series'] ?? []) > 0
        : $rows->count() >= 2;
    $allowPie = $mode !== 'grouped';
@endphp

@if ($showChart)
    <div {{ $attributes->class(['rounded-xl border border-gray-200 bg-white p-4']) }} data-statistic-chart-wrapper="{{ $chartId }}">
        <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
            @if ($title)
                <h3 class="text-sm font-semibold text-gray-700">{{ $title }}</h3>
            @else
                <span></span>
            @endif

            @if ($showTypeSelector && $allowPie)
                <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-1" role="group" aria-label="Tipe grafik">
                    <button
                        type="button"
                        data-statistic-chart-type="bar"
                        data-chart-id="{{ $chartId }}"
                        @class([
                            'statistic-chart-type-btn rounded-md px-3 py-1.5 text-xs font-semibold transition',
                            'statistic-chart-type-active' => $defaultType === 'bar',
                        ])
                        aria-pressed="{{ $defaultType === 'bar' ? 'true' : 'false' }}"
                    >
                        Bar
                    </button>
                    <button
                        type="button"
                        data-statistic-chart-type="pie"
                        data-chart-id="{{ $chartId }}"
                        @class([
                            'statistic-chart-type-btn rounded-md px-3 py-1.5 text-xs font-semibold transition',
                            'statistic-chart-type-active' => $defaultType === 'pie',
                        ])
                        aria-pressed="{{ $defaultType === 'pie' ? 'true' : 'false' }}"
                    >
                        Pie
                    </button>
                </div>
            @elseif ($showTypeSelector && ! $allowPie)
                <span class="text-xs text-gray-500">Grafik batang (multi-kolom angka)</span>
            @endif
        </div>
        <div id="{{ $chartId }}" style="min-height: {{ $height }}px;"></div>
    </div>

    @if ($autoInit)
        @push('scripts')
            <script>
                (function () {
                    const chartId = @js($chartId);
                    const config = @js($dataset);
                    const height = @js($height);
                    const defaultType = @js($defaultType);

                    const init = () => {
                        if (typeof window.initStatisticChartTypeSelector === 'function') {
                            window.initStatisticChartTypeSelector(chartId, config, height, defaultType);
                        }
                    };

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', init);
                    } else {
                        init();
                    }
                })();
            </script>
        @endpush
    @endif
@endif
