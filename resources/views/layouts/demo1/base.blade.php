<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en">

<head>
    @include('layouts.partials.head')
    @stack('styles')
    @livewireStyles
</head>

<body class="demo1 kt-sidebar-fixed kt-header-fixed flex h-full bg-background text-base text-foreground antialiased">
    <livewire:shared.theme-toggle />

    <!-- Page -->
    <!-- Main -->
    <div class="flex grow">

        <livewire:demo1.sidebar />


        <!-- Wrapper -->
        <div class="kt-wrapper flex grow flex-col">
            @persist('mega-menu')
                <livewire:demo1.header />
            @endpersist

            <!-- Content -->
            <main class="grow pt-5" id="content" role="content">
                {{ $slot }}
            </main>
            <!-- End of Content -->

            <livewire:demo1.footer />
        </div>
        <!-- End of Wrapper -->
    </div>
    <!-- End of Main -->
    <!-- End of Page -->

    <livewire:toast />
    <script>
        document.addEventListener('livewire:navigated', () => {
            KTComponents.init();

            setTimeout(() => {
                window.RichEditor?.initAll();
            }, 150);
        });
    </script>
    @include('layouts.partials.scripts')
    @stack('scripts')
    @livewireScripts
    @vite(['resources/js/rich-editor.js'])
</body>

</html>
