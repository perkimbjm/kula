<!DOCTYPE html>
<html lang="id">
<head>
    @include('components.meta')

    <title>{{ config('app.name', 'Kula Rakat') }}</title>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('before-style')

        @include('components.style')

    @stack('after-style')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="min-h-screen flex flex-col bg-gradient-to-b from-green-700 via-green-600 to-green-700 dark:bg-green-900 antialiased font-roboto dark:text-white/50">
    <noscript>You need to enable JavaScript to run this app.</noscript>
    <x-header class="w-full" />

    <main id="main" class="flex-grow">
        @yield('content')
    </main>
    </div>
    <x-footer class="w-full" />

    <a href="#" class="back-to-top fixed bottom-4 right-4 bg-yellow-400 p-3 rounded-full hover:bg-yellow-500 transition-all">
        <span class="sr-only">Kembali ke atas</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7m0 0l7 7m-7-7H6" />
        </svg>
    </a>
    <div id="preloader"></div>

    @stack('before-script')

        @include('components.script')

    @stack('after-script')

    <!-- MapPicker Form Restoration Fix -->
    <script src="{{ asset('js/map-picker-fix.js') }}"></script>

</body>
</html>
