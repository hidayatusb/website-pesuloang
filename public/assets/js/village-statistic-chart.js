(function () {
    const colors = ['#2D5A27', '#3d7a35', '#5a9e52', '#7bc06f', '#234720', '#b8d4b4'];
    const formatNumber = (value) => new Intl.NumberFormat('id-ID').format(value);

    window.buildVillageStatisticChartOptions = function (config, height, chartType = 'bar') {
        if (config.mode === 'grouped') {
            const categories = config.categories ?? [];
            const series = config.series ?? [];

            return {
                chart: {
                    type: 'bar',
                    height,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                },
                series,
                colors,
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: categories.length > 3 ? '70%' : '55%',
                    },
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories,
                    labels: { style: { fontSize: '12px' } },
                },
                yaxis: {
                    labels: { formatter: formatNumber },
                },
                legend: { position: 'bottom' },
                grid: { borderColor: '#f1f5f9' },
                tooltip: {
                    y: { formatter: (value) => formatNumber(value) },
                },
            };
        }

        const rows = config.rows ?? config;
        const labels = rows.map((row) => row.label);
        const values = rows.map((row) => row.value);

        if (chartType === 'pie') {
            return {
                chart: {
                    type: 'pie',
                    height,
                    fontFamily: 'Inter, sans-serif',
                },
                labels,
                series: values,
                colors,
                legend: { position: 'bottom' },
                dataLabels: { enabled: true },
                tooltip: {
                    y: {
                        formatter: (value, { seriesIndex }) => {
                            const unit = rows[seriesIndex]?.unit;
                            const formatted = formatNumber(value);
                            return unit ? `${formatted} ${unit}` : formatted;
                        },
                    },
                },
            };
        }

        return {
            chart: {
                type: 'bar',
                height,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
            },
            series: [{ name: 'Nilai', data: values }],
            colors: ['#2D5A27'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: rows.length > 4,
                    columnWidth: '48%',
                },
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: labels,
                labels: { style: { fontSize: '12px' } },
            },
            yaxis: {
                labels: { formatter: formatNumber },
            },
            grid: { borderColor: '#f1f5f9' },
            tooltip: {
                y: {
                    formatter: (value, { dataPointIndex }) => {
                        const unit = rows[dataPointIndex]?.unit;
                        const formatted = formatNumber(value);
                        return unit ? `${formatted} ${unit}` : formatted;
                    },
                },
            },
        };
    };

    window.renderVillageStatisticChart = function (chartId, config, height, chartType = 'bar') {
        const element = document.getElementById(chartId);

        if (!element || typeof ApexCharts === 'undefined') {
            return;
        }

        if (element._apexChart) {
            element._apexChart.destroy();
        }

        const resolvedType = config.mode === 'grouped' ? 'bar' : chartType;
        const options = window.buildVillageStatisticChartOptions(
            { ...config, title: config.title ?? '' },
            height,
            resolvedType,
        );
        const chart = new ApexCharts(element, options);
        element._apexChart = chart;
        chart.render();
    };

    window.setStatisticChartTypeButtonState = function (chartId, chartType) {
        const wrapper = document.querySelector(`[data-statistic-chart-wrapper="${chartId}"]`);

        if (!wrapper) {
            return;
        }

        wrapper.querySelectorAll('[data-statistic-chart-type]').forEach((button) => {
            const isActive = button.dataset.statisticChartType === chartType;

            button.classList.toggle('statistic-chart-type-active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    };

    window.initStatisticChartTypeSelector = function (chartId, config, height, defaultType = 'bar') {
        const wrapper = document.querySelector(`[data-statistic-chart-wrapper="${chartId}"]`);

        if (!wrapper) {
            return;
        }

        const storageKey = `statisticChartType:${chartId}`;
        const allowPie = config.mode !== 'grouped';
        let chartType = defaultType;

        wrapper._statisticChartId = chartId;
        wrapper._statisticChartConfig = config;
        wrapper._statisticChartHeight = height;

        if (allowPie) {
            chartType = sessionStorage.getItem(storageKey) || defaultType;
            if (!['bar', 'pie'].includes(chartType)) {
                chartType = defaultType;
            }
        }

        window.setStatisticChartTypeButtonState(chartId, chartType);
        window.renderVillageStatisticChart(chartId, config, height, chartType);

        if (!allowPie || wrapper.dataset.selectorBound === 'true') {
            return;
        }

        wrapper.dataset.selectorBound = 'true';
        wrapper.addEventListener('click', (event) => {
            const button = event.target.closest('[data-statistic-chart-type]');

            if (!button || !wrapper.contains(button)) {
                return;
            }

            const nextType = button.dataset.statisticChartType;

            if (!['bar', 'pie'].includes(nextType)) {
                return;
            }

            const activeChartId = wrapper._statisticChartId;
            const activeConfig = wrapper._statisticChartConfig;
            const activeHeight = wrapper._statisticChartHeight;
            const activeStorageKey = `statisticChartType:${activeChartId}`;

            sessionStorage.setItem(activeStorageKey, nextType);
            window.setStatisticChartTypeButtonState(activeChartId, nextType);
            window.renderVillageStatisticChart(activeChartId, activeConfig, activeHeight, nextType);
        });
    };
})();
