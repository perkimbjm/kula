<section id="info" class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
        <div class="space-y-12">
            <span class="text-purple-200 text-2xl font-bold">About us</span>
            <h2 class="text-2xl md:text-3xl font-bold text-white">
                Dinas Pekerjaan Umum, Penataan Ruang, Perumahan Rakyat, dan Kawasan Permukiman Kab. Balangan
            </h2>
            <blockquote class="text-purple-100 border-l-4 border-purple-300 pl-4 italic">
                "Negara bertanggung jawab melindungi segenap bangsa Indonesia melalui penyelenggaraan perumahan dan kawasan permukiman agar masyarakat mampu bertempat tinggal serta menghuni rumah yang layak dan terjangkau di dalam lingkungan yang sehat, aman, harmonis, dan berkelanjutan di seluruh wilayah Indonesia." (UU no. 1 tahun 2011)
            </blockquote>
        </div>
        <div class="px-2 overflow-hidden">
            <img src="{{ asset('img/tugu.png') }}" 
                 alt="Team collaboration" 
                 class="w-full h-auto object-cover">
        </div>
    </div>

    <div class="space-y-8">
        <h2 class="text-3xl font-bold text-purple-200">Informasi</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @if(empty($information) || $information->isEmpty())
                <p class="text-white">Tidak ada informasi yang tersedia.</p>
            @else
                @foreach ($information as $info)
                <article class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform hover:scale-[1.02]">
                    <div class="aspect-w-16 aspect-h-9">
                        @if($info->thumbnail)
                            <img src="{{ asset('storage/' . $info->thumbnail) }}" 
                                 alt="gambar" 
                                 class="w-full h-48 object-cover lazy"
                                 loading="lazy">
                        @else
                            <img src="{{ asset('img/placeholder-info.jpg')}}" 
                                 alt="Default" 
                                 class="w-full h-48 object-cover lazy"
                                 loading="lazy">
                        @endif
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="{{ $info['info_url'] }}" class="block group" target="_blank">
                            <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                                {{ $info['title'] }}
                            </h3>
                        </a>
                        <p class="text-sm text-gray-500">
                            Tanggal Posting: {{ $info->created_at->diffForHumans() }}
                        </p>
                        <p class="text-sm text-gray-600 line-clamp-3">
                            {{ Str::limit($info['description'], 300) }}
                        </p>
                        <a href="{{ $info['info_url'] }}" 
                           class="inline-block text-blue-500 hover:text-blue-700 font-medium transition-colors"
                           target="_blank">
                            Read More &raquo;
                        </a>
                    </div>
                </article>
                @endforeach
            @endif
        </div>
    </div>
</section>
