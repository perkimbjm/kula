<section id="info" class="container mx-auto px-4 py-20">
    <div class="grid md:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-purple-300 mb-2 inline-block">About us</span>
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-6">
                Dinas Pekerjaan Umum, Penataan Ruang, Perumahan Rakyat, dan Kawasan Permukiman Kab. Balangan
            </h2>
            <quote class="text-purple-100">
                " Negara bertanggung jawab melindungi segenap bangsa Indonesia melalui penyelenggaraan perumahan dan kawasan permukiman agar masyarakat mampu bertempat tinggal serta menghuni rumah yang layak dan terjangkau di dalam lingkungan yang sehat, aman, harmonis, dan berkelanjutan di seluruh wilayah Indonesia." (UU no. 1 tahun 2011)
            </quote>
        </div>
        <div class="px-2">
            <img src="{{ asset('img/tugu.png') }}" 
                 alt="Team collaboration" 
                 class="w-full object-cover">
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white">Informasi</h1>
      </div>
      
      <div class="container mx-auto px-4">
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @if(empty($information) || $information->isEmpty())
              <p>Tidak ada informasi yang tersedia.</p>
            @else
            @foreach ($information as $info)
            <article class="bg-white p-4 rounded-lg shadow-md max-w-screen-md border-b border-gray-300 mt-4 flex flex-col">
                <div class="flex flex-col">
                    <a href="{{ $info['info_url'] }}" class="hover:underline" target="_blank">
                        <h2 class="text-lg font-semibold text-gray-800 hover:text-blue-800">{{ $info['title'] }}</h2>
                    </a>
                    <p class="text-sm text-gray-500 mt-2">Tanggal Posting: {{ $info->created_at->diffForHumans() }}</p>
                </div>
                <div class="mt-4">
                    @if($info->thumbnail)
                        <img src="{{ asset('storage/' . $info->thumbnail) }}" alt="gambar" class="w-full h-48 object-cover rounded-md lazy" loading="lazy">
                    @else
                        <img src="{{ asset('img/placeholder-info.jpg')}}" alt="Default" class="w-full h-48 object-cover rounded-md lazy" loading="lazy">
                    @endif
                </div>
                <p class="text-sm text-gray-600 my-4 font-light">{{ Str::limit($info['description'], 100) }}</p>
                
                <a href="{{ $info['info_url'] }}" class="text-blue-500 hover:text-blue-800 mt-4 inline-block" target="_blank">Read More &raquo;</a>
            </article>
      
            @endforeach
            @endif
          
          </div>
      </div>
</section>
