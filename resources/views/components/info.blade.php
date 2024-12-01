<section id="info" class="container mx-auto px-4 py-20">
    <div class="grid md:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-purple-300 mb-2 inline-block">About us</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Our Journey, Vision, And Values
            </h2>
            <p class="text-purple-100">
                Lorem Ipsum Is Simply Dummy Text Of The Printing And Typesetting Industry. Lorem Ipsum Has Been The Industry's
            </p>
            <div class="mt-4 inline-flex items-center bg-purple-500/10 px-4 py-2 rounded-full">
                <span class="text-purple-200 mr-2">★★★★★</span>
                <span class="text-white">5 Star Reviews</span>
            </div>
        </div>
        <div class="bg-purple-400/20 rounded-3xl p-4 backdrop-blur-sm">
            <img src="{{ asset('images/placeholder.svg?height=400&width=600') }}" 
                 alt="Team collaboration" 
                 class="w-full rounded-2xl object-cover">
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white">Informasi</h1>
      </div>
      
      <div class="container mx-auto px-4">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          {{-- @foreach ($galleries as $gallery)
          <article class="bg-white p-4 rounded-lg shadow-md max-w-screen-md border-b border-gray-300 mt-4">
              <a href="/gallery/{{ $gallery['slug'] }}" class="hover:underline">
                <h2 class="text-lg font-semibold text-gray-800 hover:text-blue-800">{{ $gallery['title'] }}</h2>
              </a>
              <p class="text-base text-gray-500 mt-2">Tanggal Posting: {{ $gallery->created_at->diffForHumans() }}</p>
              <img src="{{ $gallery['image'] }}" alt="{{ $gallery['alt'] }}" class="w-full h-48 object-cover mt-4 rounded-md">
              <p class="text-sm text-gray-600 my-4 font-light">{{ Str::limit($gallery['description'], 50) }}</p>
              
              <a href="/gallery/{{ $gallery['slug'] }}" class="text-blue-500 hover:text-blue-800 mt-4 inline-block">Read More &raquo;</a>
          </article>
      
          @endforeach --}}
          <article class="bg-white p-4 rounded-lg shadow-md max-w-screen-md border-b border-gray-300 mt-4">
            <a href="#" class="hover:underline">
              <h2 class="text-lg font-semibold text-gray-800 hover:text-blue-800">Perkim Balangan</h2>
            </a>
            <p class="text-base text-gray-500 mt-2">Tanggal Posting: 04-05-2023</p>
            <img src="{{ asset('img/tugu.png')}}" alt="tes" class="w-full h-48 object-cover mt-4 rounded-md">
            <p class="text-sm text-gray-600 my-4 font-light">lorem ipsum dolor bla bla bla</p>
            
            <a href="" class="text-blue-500 hover:text-blue-800 mt-4 inline-block">Read More &raquo;</a>
        </article>

        <article class="bg-white p-4 rounded-lg shadow-md max-w-screen-md border-b border-gray-300 mt-4">
            <a href="#" class="hover:underline">
              <h2 class="text-lg font-semibold text-gray-800 hover:text-blue-800">Perkim Balangan</h2>
            </a>
            <p class="text-base text-gray-500 mt-2">Tanggal Posting: 04-05-2023</p>
            <img src="{{ asset('img/tugu.png')}}" alt="tes" class="w-full h-48 object-cover mt-4 rounded-md">
            <p class="text-sm text-gray-600 my-4 font-light">lorem ipsum dolor bla bla bla</p>
            
            <a href="" class="text-blue-500 hover:text-blue-800 mt-4 inline-block">Read More &raquo;</a>
        </article>

        <article class="bg-white p-4 rounded-lg shadow-md max-w-screen-md border-b border-gray-300 mt-4">
            <a href="#" class="hover:underline">
              <h2 class="text-lg font-semibold text-gray-800 hover:text-blue-800">Perkim Balangan</h2>
            </a>
            <p class="text-base text-gray-500 mt-2">Tanggal Posting: 04-05-2023</p>
            <img src="{{ asset('img/register.png')}}" alt="tes" class="w-full h-48 object-cover mt-4 rounded-md">
            <p class="text-sm text-gray-600 my-4 font-light">lorem ipsum dolor bla bla bla</p>
            
            <a href="" class="text-blue-500 hover:text-blue-800 mt-4 inline-block">Read More &raquo;</a>
        </article>

        <article class="bg-white p-4 rounded-lg shadow-md max-w-screen-md border-b border-gray-300 mt-4">
            <a href="#" class="hover:underline">
              <h2 class="text-lg font-semibold text-gray-800 hover:text-blue-800">Perkim Balangan</h2>
            </a>
            <p class="text-base text-gray-500 mt-2">Tanggal Posting: 04-05-2023</p>
            <img src="{{ asset('img/login.png')}}" alt="tes" class="w-full h-48 object-cover mt-4 rounded-md">
            <p class="text-sm text-gray-600 my-4 font-light">lorem ipsum dolor bla bla bla</p>
            
            <a href="" class="text-blue-500 hover:text-blue-800 mt-4 inline-block">Read More &raquo;</a>
        </article>

        <article class="bg-white p-4 rounded-lg shadow-md max-w-screen-md border-b border-gray-300 mt-4">
            <a href="#" class="hover:underline">
              <h2 class="text-lg font-semibold text-gray-800 hover:text-blue-800">Perkim Balangan</h2>
            </a>
            <p class="text-base text-gray-500 mt-2">Tanggal Posting: 04-05-2023</p>
            <img src="{{ asset('img/login.png')}}" alt="tes" class="w-full h-48 object-cover mt-4 rounded-md">
            <p class="text-sm text-gray-600 my-4 font-light">lorem ipsum dolor bla bla bla</p>
            
            <a href="" class="text-blue-500 hover:text-blue-800 mt-4 inline-block">Read More &raquo;</a>
        </article>

        <article class="bg-white p-4 rounded-lg shadow-md max-w-screen-md border-b border-gray-300 mt-4">
            <a href="#" class="hover:underline">
              <h2 class="text-lg font-semibold text-gray-800 hover:text-blue-800">Perkim Balangan</h2>
            </a>
            <p class="text-base text-gray-500 mt-2">Tanggal Posting: 04-05-2023</p>
            <img src="{{ asset('img/login.png')}}" alt="tes" class="w-full h-48 object-cover mt-4 rounded-md">
            <p class="text-sm text-gray-600 my-4 font-light">lorem ipsum dolor bla bla bla</p>
            
            <a href="" class="text-blue-500 hover:text-blue-800 mt-4 inline-block">Read More &raquo;</a>
        </article>
          </div>
      </div>
</section>