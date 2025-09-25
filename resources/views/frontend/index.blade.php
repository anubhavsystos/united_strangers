@extends('layouts.frontend')
@push('title', get_phrase('Blogs'))
@push('meta')@endpush
@section('frontend_layout')
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add in your <head> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
      #scrollNav { transform: translateY(0); } #scrollNav.-translate-y-full
      { transform: translateY(-100%); } ::-webkit-scrollbar { display: none;
      } h1 { font-family: 'WinnerCondBlack', sans-serif; } p { font-family: 'Bernier',
      sans-serif; } .video-container iframe { width: 100%; height: 100%; object-fit:
      cover; } @font-face { font-family: 'WinnerCondBlack'; src: url('/fonts/Winner-CondBlack.woff2');
      } @font-face { font-family: 'Borsok'; src: url('/fonts/Borsok.woff2');
      } @font-face { font-family: 'Anton'; src: url('/fonts/Anton.woff2'); }
      @font-face { font-family: 'Bernier'; src: url('/fonts/Bernier.woff2');
      } @font-face { font-family: 'Choxr'; src: url('/fonts/Choxr.woff2'); }
      .logo-font { font-family: 'WinnerCondBlack', 'Borsok', sans-serif; } .content-font
      { font-family: 'Anton', 'Bernier', sans-serif; } .header-font { font-family:
      'Choxr', sans-serif; }

      .divide-black > :not([hidden]) ~ :not([hidden]) {
        --tw-divide-opacity: 1;
        padding: 10px 40px;
        border-color: rgb(0 0 0 / var(--tw-divide-opacity, 1));
    } 
    </style>
   
    <section class="relative w-full h-screen overflow-hidden" id="video-section">
      <img src="https://i0.wp.com/acehotel.com/wp-content/uploads/2024/07/brand-homepage-fallback.jpg?resize=1920%2C1080&ssl=1" class="absolute inset-0 w-full h-full object-cover z-0" alt="Fallback" />
      <div class="video-container absolute inset-0 z-10">
        <iframe id="videoFrame" src="https://videopress.com/embed/1ZBvLm4i?cover=1&autoPlay=1&controls=0&loop=1&muted=1&persistVolume=0&playsinline=1&preloadContent=metadata&useAverageColor=1&hd=1" frameborder="0" allowfullscreen class="w-full h-full object-cover"> </iframe>
      </div>
      <div class="absolute inset-0 bg-black/50 z-20"> </div>
      
      <div class="relative z-20 flex flex-col items-center justify-center h-full text-center px-4">
        <h1 class="text-5xl md:text-7xl font-bold uppercase leading-tight text-white">
          Welcome to <br />United Strangers</h1>
        <button onclick="document.getElementById('about-section').scrollIntoView({ behavior: 'smooth' });"   class="mt-12 animate-bounce" aria-label="Scroll to description">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>
      <button id="togglePlay" class="absolute bottom-5 right-5 z-30 w-12 h-12 rounded-full bg-white/30 backdrop-blur flex items-center justify-center text-white hover:bg-white/50 transition" aria-label="Toggle video play">
        <svg id="playIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="white" viewBox="0 0 24 24">
          <path d="M8 5v14l11-7z" />
        </svg>
      </button>
    </section>     
    <section class="px-4 md:px-10 py-12 bg-gray-50">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h2 class="text-5xl font-black uppercase" style="font-family: 'CHOXR', sans-serif;"> Sleep </h2>
          <p class="text-sm text-gray-600 mt-2"> Available at most United Strangers. </p>
        </div>
        <a href="{{ route('listing.view', ['type' => 'sleep', 'view' => 'grid']) }}" class="text-sm underline flex items-center gap-1"> See All <span> → </span> </a>
      </div>

      <!-- Slider Container -->
      <div class="relative">
        <div id="sleep-slider" class="flex overflow-x-auto scroll-smooth snap-x gap-6 pb-6">
          @foreach($sleeplistings as $item)
          <div class="min-w-[280px] max-w-sm bg-white rounded-md snap-start shadow-md">
            <div class="single-grid-card">
              <!-- Banner Slider -->
              <div class="grid-slider-area relative">
                <a class="block w-full h-full" href="{{ $item['details_url'] }}">
                  <img class="card-item-image w-full h-48 object-cover rounded-t-md" src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}">
                </a>
  
                @if($item['is_popular'])
                    <p class="absolute top-2 left-2 bg-black text-white text-xs px-2 py-1 rounded">{{ $item['is_popular'] ?? '' }}</p>
                @endif
              </div>
              <div class="sleep-grid-details p-4">
                <a href="{{ $item['details_url'] }}" class="title font-semibold block mb-2">
                  @if(!empty($item['is_verified']))
                    <span data-bs-toggle="tooltip" data-bs-title="This listing is verified">  </span>
                  @endif
                  {{ $item['title'] }}
                </a>

                <div class="flex items-center justify-between mb-2 text-sm text-gray-600">
                  <div class="flex items-center gap-1">
                    <img src="/assets/frontend/images/icons/location-purple-16.svg" alt="">
                    <p>{{ $item['city'] }}, {{ $item['country'] }}</p>
                  </div>
                </div>

                <ul class="flex flex-wrap gap-2 text-xs text-gray-700 mb-3">
                  @foreach(array_slice($item['features'], 0, 2) as $f)
                    <li>{{ $f }}</li>
                  @endforeach
                  @if(count($item['features']) > 4)
                    <li class="more">+{{ count($item['features']) - 4 }} More</li>
                  @endif
                </ul>

                <div class="flex items-center justify-between">
                  <a href="{{ $item['details_url'] }}" class="see-details-btn1 text-purple-700 text-white">See Details</a>
                  <div class="prices flex items-baseline gap-1">
                    <p class="price font-bold">{{ $item['price'] }}</p>
                    <p class="time text-sm text-gray-500">/night</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Arrows -->
        <button data-dir="left" class="slider-btn hidden md:flex items-center justify-center absolute left-0 top-1/2 transform -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10">
          &#8592; </button>
        <button data-dir="right" class="slider-btn hidden md:flex items-center justify-center absolute right-0 top-1/2 transform -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10">
          &#8594; </button>
      </div>
    </section>

    <section class="px-4 md:px-10 py-12 bg-gray-50">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h2 class="text-5xl font-black uppercase" style="font-family: 'CHOXR', sans-serif;"> Work </h2>
          <p class="text-sm text-gray-600 mt-2"> Available at most United Strangers. </p>
        </div>
        <a href="{{ route('listing.view', ['type' => 'work', 'view' => 'grid']) }}" class="text-sm underline flex items-center gap-1"> See All <span> → </span> </a>
      </div>
      <div class="relative">        
        <div id="work-slider" class="flex overflow-x-auto scroll-smooth snap-x gap-6 pb-6">
          @foreach($worklistings as $workitem)          
          <div class="min-w-[280px] max-w-sm bg-white rounded-md snap-start shadow-md">
            <div class="single-grid-card">
              <div class="grid-slider-area relative">
                <a class="w-100 h-100" href="{{ route('listing.details', ['type'=>'work','id'=>$workitem['id'],'slug'=>$workitem['slug']]) }}">
                  <img class="card-item-image w-full h-48 object-cover rounded-t-md" 
                      src="{{ $workitem['image'] }}" 
                      alt="{{ $workitem['title'] }}">
                </a>
                <!-- Status (Top-Left) -->
                @if(!empty($workitem['status']))
                    <p class="absolute top-2 left-2 bg-black text-white text-xs px-2 py-1 rounded">{{ $workitem['status'] ?? '' }}</p>
                @endif
                <!-- Wishlist (Top-Right) -->
                <a href="javascript:void(0);" 
                  data-bs-toggle="tooltip" 
                  data-bs-title="{{ $workitem['is_in_wishlist'] ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                  onclick="updateWishlist(this, '{{ $workitem['id'] }}')"
                  class="absolute top-2 right-2 grid-list-bookmark white-bookmark {{ $workitem['is_in_wishlist'] ? 'active' : '' }}">
                  <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M13.4 3C12.7 3 12 3.2 11.4 3.6C10.8 4 10.3 4.5 10 5.2C9.7 4.5 9.2 4 8.6 3.6C8 3.2 7.3 3 6.6 3C5.4 3.1 4.4 3.6 3.6 4.5C2.9 5.3 2.5 6.5 2.5 7.7C2.5 10.7 5.5 14 8 16.2C8.6 16.7 9.3 17 10 17C10.7 17 11.4 16.7 12 16.2C14.5 14 17.5 10.7 17.5 7.7C17.5 6.5 17.1 5.3 16.4 4.5C15.6 3.6 14.6 3.1 13.4 3Z" fill="#000"/>
                  </svg>
                </a>
              </div>

              <!-- Details -->
              <div class="reals-grid-details p-4">
                <div class="location flex items-center gap-1 text-sm text-gray-500">
                  <img src="/assets/frontend/images/icons/location-purple-16.svg" alt="">
                  <p>{{ $workitem['city'] ?? '' }}, {{ $workitem['country'] ?? '' }}</p>
                </div>

                <div class="reals-grid-title mt-2 mb-4">
                  <a href="{{ route('listing.details',['type'=>'work','id'=>$workitem['id'],'slug'=>$workitem['slug']]) }}" 
                    class="font-semibold text-lg">
                    {!! $workitem['is_verified'] ? '<span title="Verified">✔️</span>' : '' !!}
                    {{ $workitem['title'] }}
                  </a>
                  <p class="text-sm text-gray-600">
                    {{ Str::limit($workitem['description'] ?? '', 80, '...') }}
                  </p>
                </div>

                <!-- Features -->
                <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                  <div class="flex items-center gap-1">
                    <img src="/assets/frontend/images/icons/bed-gray-16.svg" alt="">
                    <p>{{ $workitem['bed'] }} Bed</p>
                  </div>
                  <div class="flex items-center gap-1">
                    <img src="/assets/frontend/images/icons/bath-gray-16.svg" alt="">
                    <p>{{ $workitem['bath'] }} Bath</p>
                  </div>
                  <div class="flex items-center gap-1">
                    <img src="/assets/frontend/images/icons/resize-arrows-gray-16.svg" alt="">
                    <p>{{ $workitem['size'] }} sqft</p>
                  </div>
                </div>

                <!-- Price + View -->
                <div class="flex justify-between items-center mt-4">
                  <div class="prices flex items-center gap-2">
                    @if(!empty($workitem['discount']))
                      <p class="new-price text-green-600 font-bold">{{ $workitem['discount'] }}</p>
                      <p class="old-price line-through text-gray-400">{{ $workitem['price'] }}</p>
                    @else
                      <p class="new-price text-green-600 font-bold">{{ $workitem['price'] }}</p>
                    @endif
                  </div>
                  <a href="{{ route('listing.details',['type'=>'work','id'=>$workitem['id'],'slug'=>$workitem['slug']]) }}" 
                    class="reals-grid-view">
                    <img src="/image/12.svg" alt="">
                  </a>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <button data-dir="left" class="slider-btn hidden md:flex items-center justify-center absolute left-0 top-1/2 -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10">
          &#8592;
        </button>
        <button data-dir="right" class="slider-btn hidden md:flex items-center justify-center absolute right-0 top-1/2 -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10">
          &#8594;
        </button>
      </div>
    </section>

    <section class="px-4 md:px-10 py-12 bg-gray-50">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h2 class="text-5xl font-black uppercase" style="font-family: 'CHOXR', sans-serif;"> Play </h2>
          <p class="text-sm text-gray-600 mt-2"> Available at most United Strangers. </p>
        </div>
        <a href="{{ route('listing.view', ['type' => 'play', 'view' => 'grid']) }}" class="text-sm underline flex items-center gap-1"> See All <span> → </span> </a>
      </div>

      <div class="relative">
        <div id="play-slider" class="flex overflow-x-auto scroll-smooth snap-x gap-6 pb-6">
          @foreach($playlistings as $item)
            <div class="min-w-[280px] max-w-sm bg-white rounded-md snap-start shadow-md overflow-hidden">
              <div class="relative">    
                <a href="{{ $item['details_url'] }}">
                  <img class="w-full h-48 object-cover" src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                </a>
                @if(!empty($item['is_popular']))
                  <span class="absolute top-2 left-2 bg-black text-white text-xs font-semibold px-2 py-1 rounded">
                    Popular
                  </span>
                @endif
              </div>

              <div class="p-4">
                <h3 class="text-base font-semibold capitalize mb-1">{{ $item['title'] }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $item['city'] ?? '' }}, {{ $item['country'] ?? '' }}</p>
                
                <div class="flex items-center justify-between mb-3">
                  <div class="flex items-center text-sm">
                    <img src="/assets/frontend/images/icons/star-yellow-16.svg" class="mr-1" alt="">
                    <span>({{ $item['reviews_count'] ?? 0 }})</span>
                  </div>
                </div>

                <ul class="flex text-xs text-gray-700 gap-2">
                  <li>Dine in</li>
                  <li>• Takeaway</li>
                  <li>• Delivery</li>
                </ul>
              </div>
            </div>
          @endforeach
        </div>
        <button data-dir="left" class="slider-btn hidden md:flex items-center justify-center absolute left-0 top-1/2 -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10">
          &#8592;
        </button>
        <button data-dir="right" class="slider-btn hidden md:flex items-center justify-center absolute right-0 top-1/2 -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10">
          &#8594;
        </button>
      </div>
    </section> 


@if(count($offers) != 0)
  <section class="px-4 md:px-10 py-12 bg-gray-50">
    <div class="flex justify-between items-center mb-8">
      <div>
        <h2 class="text-5xl font-black uppercase" style="font-family: 'CHOXR', sans-serif;">Offers </h2>
        <p class="text-sm text-gray-600 mt-2"> Available at most United Strangerss. </p>
      </div>      
    </div>
    <div class="relative">
      <div id="offer-slider" class="flex overflow-x-auto scroll-smooth snap-x gap-6 pb-6">
          @foreach($offers as $offersitem)
          <a href="{{isset($offersitem['details_url']) ? $offersitem['details_url'] : 'javascript:void(0);' }}" targat="_blank" >
        <div class="min-w-[280px] max-w-sm bg-white rounded-md snap-start shadow-md">
          <img src="{{$offersitem['image']}}" alt="Offer 1" class="w-full h-full object-cover rounded-t-md">
          <div class="p-4">
            <h3 class="text-lg md:text-xl font-bold mb-1 leading-tight" style="font-family: 'ANTON';">{{$offersitem['title']}}</h3>
            <p class="text-sm text-gray-700 mb-4">{{ $offersitem['from_date'] }} to {{ $offersitem['to_date'] }}</p>
            <p class="text-sm text-gray-700 mb-4">
                <span id="desc-short-{{ $offersitem['id'] }}">
                    {{ $offersitem['description'] }}
                </span>
                <span id="desc-full-{{ $offersitem['id'] }}" style="display: none;">
                    {{ $offersitem['full_desc'] }}
                </span>

                @if($offersitem['read_more'])
                    <a href="javascript:void(0);"  id="toggle-{{ $offersitem['id'] }}"  class="text-blue-600 font-semibold" onclick="toggleDesc({{ $offersitem['id'] }})"> Read more </a>
                @endif
            </p>
          </div>
          </a>
        </div>
          @endforeach        
      </div>
      <!-- Arrows -->
      <button data-dir="left" class="slider-btn hidden md:flex items-center justify-center absolute left-0 top-1/2 transform -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10">
        &#8592;
      </button>
      <button data-dir="right" class="slider-btn hidden md:flex items-center justify-center absolute right-0 top-1/2 transform -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10">
        &#8594;
      </button>
    </div>
  </section>
  @endif

@if(count($events) != 0)
    <section class="bg-[#222] text-white px-4 md:px-12 py-12">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <h2 class="text-4xl md:text-5xl font-black uppercase mb-4 md:mb-0" style="font-family: 'Anton', sans-serif;">Goings On</h2>
        <div class="flex items-center gap-6"> </div>
      </div>
      <div class="relative">
        <button data-dir="left" class="slider-btn hidden md:flex items-center justify-center absolute left-0 top-1/2 transform -translate-y-1/2 bg-white text-black w-10 h-10 rounded-full z-10">
          &#8592;
        </button>
        <button data-dir="right" class="slider-btn hidden md:flex items-center justify-center absolute right-0 top-1/2 transform -translate-y-1/2 bg-white text-black w-10 h-10 rounded-full z-10">
          &#8594;
        </button>
        <!-- Scrollable Cards -->
        <div id="events-slider" class="flex overflow-x-auto scroll-smooth gap-6 snap-x pb-6">
          @foreach($events as $eventsitem)
          <a href="{{isset($eventsitem['details_url']) ? $eventsitem['details_url'] : 'javascript:void(0);' }}" targat="_blank" >
          <div class="min-w-[260px] max-w-xs snap-start">
            <img src="{{$eventsitem['image']}}" alt="" class="w-full h-56 object-cover mb-3">
            <h3 class="text-lg font-bold leading-tight uppercase">{{$eventsitem['title']}}</h3>
            <p class="text-xs mt-1">{{ $eventsitem['from_date'] }} to {{ $eventsitem['to_date'] }}</p>
            <p class="text-sm  leading-tight  mb-4">
                <span id="desc-short-{{ $eventsitem['id'] }}">{{ $eventsitem['description'] }}</span>
                <span id="desc-full-{{ $eventsitem['id'] }}" style="display: none;">{{ $eventsitem['full_desc'] }}</span>
                @if($eventsitem['read_more'])
                    <a href="javascript:void(0);"  id="toggle-{{ $eventsitem['id'] }}"  class="text-blue-600 font-semibold" onclick="toggleDesc({{ $eventsitem['id'] }})"> Read more </a>
                @endif
            </p>
          </div>
          </a>
          @endforeach 
          
        </div>
        <button data-dir="left" class="slider-btn hidden md:flex items-center justify-center absolute left-0 top-1/2 transform -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10"> &#8592;</button>
        <button data-dir="right" class="slider-btn hidden md:flex items-center justify-center absolute right-0 top-1/2 transform -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10">&#8594;</button>
      </div>
    </section>
@endif
@if(!empty($blogs))
    <section class="px-4 md:px-10 py-12 bg-gray-50">
      <div class="flex justify-between items-center mb-8">
        <div> <h2 class="text-5xl font-black uppercase" style="font-family: 'CHOXR', sans-serif;"> Blog</h2></div>        
      </div>
      <div class="relative">
        <div id="blog-slider" class="flex overflow-x-auto scroll-smooth snap-x gap-6 pb-6">
          @foreach($blogs as $blogsitem)
          <a href="{{isset($blogsitem['details_url']) ? $blogsitem['details_url'] : 'javascript:void(0);' }}" targat="_blank" >
            <div class="min-w-[300px] max-w-sm flex-shrink-0 snap-center">
              <img src="{{$blogsitem['image']}}" alt="Card 4" class="w-full h-64 object-cover rounded">
              <p class="text-xs mt-2 text-gray-500">{{$blogsitem['date']}}</p>
              <h3 class="text-lg font-extrabold mt-1">{{$blogsitem['title']}}</h3>
              <p class="text-sm mt-1 text-gray-700"> {!!$blogsitem['description']!!}</p>
            </div>
          </a>
          @endforeach 
        </div>
        <button data-dir="left" class="slider-btn hidden md:flex items-center justify-center absolute left-0 top-1/2 transform -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10"> &#8592;</button>
        <button data-dir="right" class="slider-btn hidden md:flex items-center justify-center absolute right-0 top-1/2 transform -translate-y-1/2 bg-white border border-gray-400 rounded-full w-12 h-12 shadow-md z-10">&#8594;</button>
      </div>
    </section>
@endif  

<script>
document.querySelectorAll('.slider-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    // find the closest slider in the same section
    const slider = this.closest('.relative').querySelector('div.flex.overflow-x-auto');
    if (!slider) return;

    const dir = this.getAttribute('data-dir');
    slider.scrollBy({
      left: dir === 'left' ? -300 : 300,
      behavior: 'smooth'
    });
  });
});
</script>

@endsection