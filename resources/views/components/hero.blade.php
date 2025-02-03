<section id="hero" class="w-full max-w-7xl mx-auto px-4 lg:py-8 pb-14 text-center">
    <div class="text-center space-y-8">
        <img src="{{ asset('img/logo.png') }}" class="w-[10rem] mx-auto" alt="logo kularakat">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 max-w-4xl mx-auto leading-tight">→ KULA RAKAT</h1>
        <h2 class="text-2xl md:text-4xl font-semibold text-white mb-6 max-w-3xl mx-auto leading-tight">
            Kolaborasi Usulan Laporan Aduan Perumahan Rakyat dan Kawasan Permukiman
        </h2>
        <div>
            <a href="/app" class="bg-yellow-400 text-gray-300 px-8 py-2 rounded-full hover:bg-yellow-500 hover:font-semibold hover:text-gray-200 transition">
                {{ Auth::check() ? 'Dashboard' : 'Login →' }}
            </a>
        </div>
    </div>

    <div class="px-2 max-w-6xl mx-auto">
        <div class="rounded-3xl p-2">
            <img src="{{ asset('img/hero-image.png') }}" 
                 alt="Pelayanan Publik" 
                 class="w-full rounded-xl object-cover">
        </div>
    </div>
</section>