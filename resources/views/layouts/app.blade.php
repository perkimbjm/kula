<!DOCTYPE html>
<html lang="id">
<head>
    @include('components.meta')

    <title>{{ config('app.name', 'Kula Rakat') }}</title>
    <link rel=preconnect href="https://fonts.googleapis.com/css2?family=Lato:wght@300&family=Oswald:wght@300&family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{ asset('js/manifest.webmanifest') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="antialiased overflow-x-hidden h-full font-roboto dark:bg-black dark:text-white/50">
    <noscript>You need to enable JavaScript to run this app.</noscript>
    <x-header />
    
    <main id="main">
        @yield('content')
    </main>
    </div>
    <x-footer />
    
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"></a>
    <div id="preloader"></div>

    @stack('before-script')

        @include('components.script')
    
    @stack('after-script')

</body>
</html>