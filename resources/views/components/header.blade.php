<header class="bg-green-700 dark:bg-green-900">
    <nav class="container mx-auto px-4 py-4 flex flex-row items-center justify-between" x-data="{ isOpen: false }">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center"></div>
                <!-- Logo -->
                    <div class="flex flex-shrink-0 items-center justify-center">
                        <img class="h-12 px-1" src="{{ asset('img/logobalangan-nav.webp') }}" alt="Logo Balangan">
                        <span class="text-white text-xl font-semibold font-roboto px-1 text-shadow">PERKIM BALANGAN</span>
                    </div>
                </div>
            </div>
            <!-- Desktop Navigation Links -->
            <div class="hidden md:block ml-auto">
                <div class="ml-10 flex items-baseline space-x-4">
                    <x-nav-link href="/" :active="request()->is('home')">Beranda</x-nav-link>
                    <x-nav-link href="/map" :active="request()->is('map')">Peta</x-nav-link>
                    <x-nav-link href="/#info" :active="request()->is('info')">Informasi</x-nav-link>
                    <x-nav-link href="/guide" :active="request()->is('guide')">Panduan</x-nav-link>
                </div>
            </div>
    
            <!-- Mobile Menu Button -->
            <div class="-mr-2 flex md:hidden">
                <button type="button" @click="isOpen = !isOpen"
                    class="relative inline-flex items-center justify-center rounded-md bg-transparent p-2 text-white hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" 
                    aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Icon Burger (Closed) -->
                    <svg :class="{'hidden': isOpen, 'block': !isOpen }" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <!-- Icon Close (Opened) -->
                    <svg :class="{'block': isOpen, 'hidden': !isOpen  }" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="isOpen" x-transition class="absolute z-50 inset-x-0 top-16 bg-green-700 dark:bg-green-900 text-white md:hidden hover:bg-green-600 shadow-lg"
  id="mobile-menu">
            <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                <x-nav-link href="/" :active="request()->is('home')">Beranda</x-nav-link>
                <x-nav-link href="/map" :active="request()->is('map')">Peta</x-nav-link>
                <x-nav-link href="#info" :active="request()->is('info')">Informasi</x-nav-link>
                <x-nav-link href="/guide" :active="request()->is('guide')">Panduan</x-nav-link>
            </div>
        </div>
    </nav>
</header>
