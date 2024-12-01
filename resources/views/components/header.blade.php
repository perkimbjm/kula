<header class="bg-green-700 dark:bg-green-900">
    <nav class="container mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center">
            <img class="h-12 px-1" src="{{ asset('img/logobalangan-nav.webp') }}" alt="Logo Balangan">
            <span class="text-white text-xl font-semibold font-roboto px-1 text-shadow">PERKIM BALANGAN</span>
        </div>
        <div class="hidden md:flex items-center space-x-6">
            <x-nav-link href="/" :active="request()->is('home')">Beranda</x-nav-link>
            <x-nav-link href="/map" :active="request()->is('map')">Peta</x-nav-link>
            <x-nav-link href="#info" :active="request()->is('info')">Informasi</x-nav-link>
            <x-nav-link href="/guide" :active="request()->is('guide')">Panduan</x-nav-link>

        </div>
    </nav>
</header>