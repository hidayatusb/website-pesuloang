<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Website resmi Desa Sukamaju, Kec. Cikarang Utara, Kab. Bekasi" />
    <title>@yield('title', 'Desa Sukamaju')</title>
    <link rel="icon" href="{{ asset('assets/media/app/favicon.ico') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/keenicons/styles.bundle.css') }}" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('assets/vendors/apexcharts/apexcharts.css') }}" rel="stylesheet" />
    <style>
        .statistic-chart-type-btn { color: #4b5563; }
        .statistic-chart-type-btn.statistic-chart-type-active {
            background-color: #2D5A27;
            color: #fff;
            box-shadow: 0 1px 2px rgb(0 0 0 / 0.05);
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans text-gray-800 antialiased">
    <div class="landing-page flex min-h-screen flex-col bg-gray-50">
        @include('layouts.partials.public-header')

        <main class="flex-1">
            @yield('content')
        </main>

        @include('layouts.partials.public-footer')
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            menu.classList.toggle('hidden');
            icon.classList.toggle('ki-menu');
            icon.classList.toggle('ki-cross');
        }
    </script>
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/village-statistic-chart.js') }}"></script>
    @stack('scripts')
</body>
</html>
