@extends('layouts.frontend')
@section('title', get_phrase('Sleep Listing Details'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/mapbox-gl.css') }}">
    <script src="{{ asset('assets/frontend/js/mapbox-gl.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/magnific-popup.css') }}">
    <script src="{{ asset('assets/frontend/js/jquery.magnific-popup.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/flatpickr.min.css') }}">
    <script src="{{ asset('assets/frontend/js/flatpickr.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/venobox.min.css') }}">
    <script src="{{ asset('assets/frontend/js/venobox.min.js') }}"></script>
@endpush
@section('frontend_layout')
    <section>
        <div class="container">
            <div class="row row-28 align-items-center mb-4">
                <div class="col-xl-8 col-lg-7">
                    <div class="sleepdetails-rent-area d-flex align-items-center justify-content-between flex-wrap">
                           <h1 class="title">{{$listing->title}}</h1>
                        <p class="title capitalize">{{-- $listing->is_popular --}}</p>
                        <div class="sleeprent-price-area d-flex align-items-center flex-wrap">
                            <!-- <p class="price">{{ get_phrase('Total Price : ') }}<span>{{ currency($listing->price) }}</span></p> -->
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="detailstop-share-back justify-content-end d-flex align-items-center flex-wrap">
                        @php
                            $is_in_wishlist = check_wishlist_status($listing->id, $listing->type);
                        @endphp                     
                        <a href="{{ route('listing.view', ['type' => 'sleep', 'view' => 'grid']) }}" class="back-btn1">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.9752 15.6834C7.81686 15.6834 7.65853 15.6251 7.53353 15.5001L2.4752 10.4418C2.23353 10.2001 2.23353 9.8001 2.4752 9.55843L7.53353 4.5001C7.7752 4.25843 8.1752 4.25843 8.41686 4.5001C8.65853 4.74176 8.65853 5.14176 8.41686 5.38343L3.8002 10.0001L8.41686 14.6168C8.65853 14.8584 8.65853 15.2584 8.41686 15.5001C8.3002 15.6251 8.13353 15.6834 7.9752 15.6834Z" fill="#7E7E89" />
                                <path d="M17.0831 10.625H3.05811C2.71644 10.625 2.43311 10.3417 2.43311 10C2.43311 9.65833 2.71644 9.375 3.05811 9.375H17.0831C17.4248 9.375 17.7081 9.65833 17.7081 10C17.7081 10.3417 17.4248 10.625 17.0831 10.625Z" fill="#7E7E89" />
                            </svg>
                            <span>{{ get_phrase('Back to listing') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row row-28 mb-80px">
                <div class="col-xl-8 col-lg-7">
                    <div class="swiper atn-banner-slider mb-30px">
                        <div class="swiper-wrapper">
                            @foreach (json_decode($listing->image) as $key => $image)
                                <div class="swiper-slide">
                                    <div class="atn-slide-banner">
                                        <img src="{{ get_all_image('listing-images/' . $image) }}" alt="">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <!-- Title Area -->
                    <div class="sleep-details-title mb-30px">                       
                        <h1 class="title mb-20">{{ $listing->title }}</h1>
                        <div class="sleepdetails-location-rating mb-3 d-flex align-items-center flex-wrap">
                            <div class="location d-flex align-items-center">
                                <img src="{{ asset('assets/frontend/images/icons/location-blue2-20.svg') }}" alt="">
                                @php
                                    $city_name = App\Models\City::where('id', $listing->city)->first()->name;
                                    $country_name = App\Models\Country::where('id', $listing->country)->first()->name;
                                @endphp
                                <p>{{ $city_name . ', ' . $country_name }}</p>
                            </div>
                            <div class="rating d-flex align-items-center">
                                @php
                                    $reviews_count = App\Models\Review::where('listing_id', $listing->id)->where('user_id', '!=', $listing->user_id)->where('type', 'sleep')->where('reply_id', null)->count();
                                    $total_ratings = App\Models\Review::where('listing_id', $listing->id)->where('user_id', '!=', $listing->user_id)->where('type', 'sleep')->where('reply_id', null)->sum('rating');
                                    $average_rating = $reviews_count > 0 ? $total_ratings / $reviews_count : 0;
                                @endphp
                            </div>
                            <p class="date">{{ get_phrase('Published:') }} {{ \Carbon\Carbon::parse($listing->created_at)->format('M d, Y') }}</p>

                        </div>
                        <ul class="sleep-room-bed-sft d-flex align-items-center flex-wrap">
                            @php
                                $roomCount = App\Models\Room::where('listing_id', $listing->id)->count();
                            @endphp
                            <li>
                                <img src="{{ asset('assets/frontend/images/icons/home-gray-24.svg') }}" alt="">
                                <span>{{ $roomCount ?? 0 }} {{ get_phrase('Room') }}</span>
                            </li>
                            <li>
                                <img src="{{ asset('assets/frontend/images/icons/bed-gray-24.svg') }}" alt="">
                                <span>{{ $listing->bed }} {{ get_phrase('Bed') }}</span>
                            </li>
                            <li>
                                <img src="{{ asset('assets/frontend/images/icons/bath-gray-24.svg') }}" alt="">
                                <span>{{ $listing->bath }} {{ get_phrase('Bath') }}</span>
                            </li>
                            <!-- <li>
                                <img src="{{ asset('assets/frontend/images/icons/move-arrow-gray-24.svg') }}" alt="">
                                <span>{{ $listing->size }} {{ get_phrase('sft') }}</span>
                            </li> -->
                            <li>
                                <img src="{{ asset('assets/frontend/images/icons/move-arrow-gray-24.svg') }}" alt="">
                                <span>{{ $listing->accommodation_type }} {{ get_phrase('Accommodation Type') }}</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="at-details-description mb-6">
                        <h4 class="title mb-6">{{ get_phrase('Description') }}</h4>
                        <p class="info mb-6">
                            <span id="short-description" class="d-block">{{ Str::limit($listing->description, 400) }}</span>
                            <span id="full-description" class="d-none">{!! removeScripts($listing->description) !!}</span>
                        </p>
                        @if (strlen($listing->description) > 400)
                            <a href="javascript:void(0);" id="read-more-btn" class="icontext-link-btn" onclick="toggleDescription()">
                                <span>{{ get_phrase('Read More') }}</span>                                
                            </a>
                        @endif
                    </div>                  
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="filter_date"> Date</label>
                            <input type="date" id="filter_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="filter_in_time">In Time</label>
                            <input type="time" id="filter_in_time" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="filter_out_time">Out Time</label>
                            <input type="time" id="filter_out_time" class="form-control">
                        </div>
                        <div class="col-md-3 mt-4">
                            <button type="button" id="filterBtn" class="btn btn-primary">Check Availability</button>
                        </div>
                    </div>
                    <samp id="filter_rooms"></samp>
                    @php $plus = 1 @endphp
                    <div class="row row-28 filter_rooms_row">
                        @foreach (($rooms ?? []) as $key => $room)
                            <div class="col-sm-12">
                                <input class="form-check-input d-none" name="room[]" 
                                    type="checkbox" 
                                    value="{{ $room['id'] ?? '' }}"
                                    id="flckDefault{{ $key }}"
                                    @if(!empty($listing->room) && $listing->room !== 'null' && in_array($room['id'] ?? 0, json_decode($listing->room, true) ?? [])) checked @endif>

                                <label class="form-check-label w-100" 
                                    onclick="selectRoom('{{ $key }}', '{{ $room['id'] ?? '' }}', '{{ $room['title'] ?? 'Untitled Room' }}', '{{ $room['price'] ?? 0 }}')" 
                                    for="flckDefault{{ $key }}">

                                    <div class="card mb-3 room-checkbox  position-relative">
                                        {{-- Green Check Overlay --}}
                                        <div class="room-check position-absolute top-0 end-0 p-2 d-none" id="roomCheck{{ $key }}">
                                            <i class="fas fa-check-circle text-success fs-4"></i>
                                        </div>

                                        <div class="row g-0 h-100">
                                            {{-- Left: Room Image --}}
                                            <div class="col-md-4">
                                                @php 
                                                    $images = $room['image'] ?? []; 
                                                    $firstImage = $images[0] ?? 'default.jpg';
                                                @endphp
                                                <img src="{{ $firstImage }}" 
                                                    class="img-fluid rounded-start h-40 w-100 object-fit-cover" 
                                                    alt="Room Image">
                                            </div>

                                            {{-- Right: Room Info --}}
                                            <div class="col-md-8 room-body">
                                                <div class="card-body py-2 px-3 h-100 position-relative d-flex flex-column">

                                                    {{-- Row 1: Title | Price | Persons | Child --}}
                                                    <div class="d-flex  align-items-center mb-1 flex-wrap">
                                                        <p class="card-title mb-0 mr-3 fw-bold line-1">{{ $room['title'] ?? 'Untitled Room' }}</p>
                                                        <p class="mb-0 text-success fw-bold">{{ !empty($room['price']) ? currency($room['price']) : currency(0) }}</p>
                                                    </div>
                                                    <div class="d-flex gap-2 fs-12px text-muted mb-1">
                                                        <span><i class="fas fa-user"></i> {{ $room['person'] ?? 0 }} {{ get_phrase('Persons') }}</span>
                                                        <span>|</span>
                                                        <span><i class="fas fa-baby"></i> {{ $room['child'] ?? 0 }} {{ get_phrase('Child') }}</span>
                                                    </div>

                                                    {{-- Row 2: Room Type --}}
                                                    @if(!empty($room['room_type']))
                                                        <p class="mb-1 fs-12px">
                                                            <strong>{{ get_phrase('Room Type') }}:</strong> {{ $room['room_type'] }}
                                                        </p>
                                                    @endif

                                                    {{-- Row 3: Features --}}
                                                    @if(!empty($room['features']))
                                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                                            @foreach ($room['features'] as $fKey => $feature)
                                                                <div class="text-center" style="width: 80px;">
                                                                    <img src="{{ asset(!empty($feature['image']) ? '/' . $feature['image'] : '/image/placeholder.png') }}"
                                                                        alt="{{ $feature['name'] ?? 'Feature' }}"
                                                                        class="rounded mb-1" style="width:30px;height:30px;">
                                                                    <span class="fs-11px d-block" style="font-size: 9px;">{{ $feature['name'] ?? '' }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="hoteldetails-location-area mb-50px">
                        <h2 class="in-title3-24px mb-20px">{{ get_phrase('Location') }}</h2>
                        <div class="hoteldetails-location-header d-flex align-items-end justify-content-between flex-wrap">
                            <div class="hoteldetails-location-name">
                                @php
                                    $city_name = App\Models\City::where('id', $listing->city)->first()->name;
                                    $country_name = App\Models\Country::where('id', $listing->country)->first()->name;
                                @endphp
                                <h4 class="name">{{ $country_name }}</h4>
                                <p class="location d-flex align-items-center">
                                    <img src="{{ asset('assets/frontend/images/icons/location-blue2-20.svg') }}" alt="">
                                    <span>{{ $listing->address }}, {{ $city_name }}</span>
                                </p>
                            </div>
                            <a href="javascript:;" class="white-btn1" id="dynamicLocation">{{ get_phrase('Get Direction') }}</a>
                        </div>
                        <div class="hoteldetails-location-map mb-16">
                            <div id="map" class="h-297"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="sleepdetails-form-area mb-30px">
                    <h4 class="sub-title ">{{ get_phrase('Booking') }}</h4>                    
                    <form action="{{ route('customerBookAppointment') }}" method="post">
                        @csrf
                        <input type="hidden" name="listing_type" value="sleep">
                        <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                        <input type="hidden" name="customer_id" value="{{ $listing->id }}">
                        <div id="selectedRoomsContainer"></div>
                        <div class="sleepdetails-form-inputs mb-16">
                        <div class="mb-3">
                            <label class="form-label">Appointment Date</label>
                            <input type="date" class="appointmentDate form-control mform-control flat-input-picker3 " name="date">
                        </div>
                        <div class="mb-3 row">
                            <div class="col">
                                <label class="form-label">Check In Time</label>
                                <input type="time" class="form-control" name="in_time" required>
                            </div>
                            <div class="col">
                            <label class="form-label"> Check Out Time</label>
                            <input type="time" class="form-control" name="out_time" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col">
                                <label class="form-label"> Adults</label>
                                <input type="number" class="form-control" name="adults" required>
                            </div>
                            <div class="col">
                                <label class="form-label">   Child</label>
                                <input type="number" class="form-control" name="child" >
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="message" id="message" cols="30" rows="3" placeholder="{{ get_phrase('Write your description') }}" class="form-control"></textarea>                        
                        </div>                        

                        <div id="bookingSummary" class="card d-none mb-4 mt-3">
                            <div class="card-header fw-bold">{{ get_phrase('Booking Summary') }}</div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ get_phrase('Room') }}</th>
                                                <th class="text-end">{{ get_phrase('Rate') }}</th>
                                                <th class="text-center">{{ get_phrase('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="summaryRooms"></tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-end">{{ get_phrase('Subtotal') }}</th>
                                                <th class="text-end" id="summarySubtotal">₹0</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" class="text-end">{{ get_phrase('Tax') }} (<span id="taxPercent">0</span>%)</th>
                                                <th class="text-end" id="summaryTax">₹0</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" class="text-end">{{ get_phrase('Total') }}</th>
                                                <th class="text-end fw-bold" id="summaryTotal">₹0</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                        </div>

                        <input type="hidden" id="tax_persent" value="{{ isset($listing->tax_persent) ? $listing->tax_persent : 0 }}">
                        <input type="hidden" id="total_price" name="total_price" value="0">

                        <button type="submit" class="submit-fluid-btn">
                        {{ get_phrase('Proceed Booking') }}
                        </button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="related-product-title mb-20">{{ get_phrase('Related sleeps') }}</h1>
                </div>
            </div>
            <div class="row row-28 mb-80">
                @php
                    $relatedListing = App\Models\SleepListing::where('is_popular', $listing->is_popular)->where('id', '!=', $listing->id)->take(4)->get();
                @endphp
                @foreach ($relatedListing->sortByDesc('created_at') as $listings)
                 
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="single-grid-card htd-grid-card">
                            <!-- Banner Slider -->
                            <div class="grid-slider-area">
                                @php
                                    $images = json_decode($listings->image);
                                    $image = isset($images[0]) ? $images[0] : null;
                                @endphp
                                <a class="w-100 h-100" href="{{route('listing.details',['type'=>$type, 'id'=>$listings->id, 'slug'=>slugify($listings->title)])}}">
                                    <img class="card-item-image" src="{{ get_all_image('listing-images/' . $image) }}">
                                </a>
                                <p class="card-light-text theme-light capitalize">{{ $listings->is_popular }}</p>
                               
                            </div>
                            <div class="sleep-grid-details position-relative">
                                <a href="{{ route('listing.details', ['type' => $type, 'id' => $listings->id, 'slug' => slugify($listing->title)]) }}" class="title"> 
                                   
                                    {{ $listings->title }} </a>
                                <div class="sleepgrid-location-rating d-flex align-items-center justify-content-between flex-wrap">
                                    <div class="location d-flex">
                                        <img src="{{ asset('assets/frontend/images/icons/location-gray-16.svg') }}" alt="">
                                        @php
                                            $city_name = App\Models\City::where('id', $listings->city)->first()->name;
                                            $country_name = App\Models\Country::where('id', $listings->country)->first()->name;
                                        @endphp
                                        <p class="name"> {{ $city_name . ', ' . $country_name }} </p>
                                    </div>
                                </div>
                                <ul class="sleepgrid-list-items d-flex align-items-center flex-wrap">
                                    @php
                                        if (isset($listing->feature) && is_array(json_decode($listing->feature))) {
                                            $features = json_decode($listing->feature);
                                            foreach ($features as $key => $item) {
                                                $feature = App\Models\Amenities::where('id', $item)->first();
                                                if ($key < 2) {
                                                    echo '<li>' . removeScripts($feature->name) . '</li>';
                                                }
                                            }
                                            $more_amenities = count(json_decode($listing->feature));
                                            if ($more_amenities > 4) {
                                                echo "<li class='more'>+" . ($more_amenities - 4) . ' ' . get_phrase('More') . '</li>';
                                            }
                                        }
                                    @endphp
                                </ul>
                                <div class="sleepgrid-see-price d-flex align-items-center justify-content-between">
                                    <a href="{{ route('listing.details', ['type' => $type, 'id' => $listings->id, 'slug' => slugify($listings->title)]) }}" class="see-details-btn1 stretched-link">{{ get_phrase('See Details') }}</a>
                                    <div class="prices d-flex">
                                        <p class="price">{{ currency($listing->price) }}</p>
                                        <p class="time">/{{ get_phrase('night') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
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
 
@endsection
@push('js')
<script>
        "use strict";
        mapboxgl.accessToken = '{{ get_settings('map_access_token') }}';
        const latitude = {{ $listing->Latitude }};
        const longitude = {{ $listing->Longitude }};
        const listingName = "{{ $listing->title }}";
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [longitude, latitude],
            zoom: 1
        });
        const popup = new mapboxgl.Popup({
                offset: 25,
                closeButton: false,
                closeOnClick: false
            })
            .setText(listingName)
            .setLngLat([longitude, latitude])
            .addTo(map);
        new mapboxgl.Marker()
            .setLngLat([longitude, latitude])
            .addTo(map);
        const nav = new mapboxgl.NavigationControl();
        map.addControl(nav, 'top-right');
    </script>
    <script>
        "use strict";

        function toggleDescription() {
            var shortDesc = document.getElementById("short-description");
            var fullDesc = document.getElementById("full-description");
            var readMoreBtn = document.getElementById("read-more-btn");

            if (shortDesc.classList.contains("d-block")) {
                shortDesc.classList.remove("d-block");
                shortDesc.classList.add("d-none");
                fullDesc.classList.remove("d-none");
                fullDesc.classList.add("d-block");
                readMoreBtn.querySelector("span").textContent = "Read Less";
            } else {
                shortDesc.classList.remove("d-none");
                shortDesc.classList.add("d-block");
                fullDesc.classList.remove("d-block");
                fullDesc.classList.add("d-none");
                readMoreBtn.querySelector("span").textContent = "Read More";
            }
        }
    </script>



<script>
let selectedRooms = [];

function selectRoom(key, roomId, roomName, price) {
    @if(auth()->check())
        let checkbox = document.getElementById('flckDefault' + key);
        checkbox.checked = !checkbox.checked;

        let checkIcon = document.getElementById('roomCheck' + key);

        if (checkbox.checked) {
            checkIcon.classList.remove('d-none');
            addRoom(roomId, roomName, price);
        } else {
            checkIcon.classList.add('d-none');
            removeRoom(roomId);
        }

        updateSummary();
    @else
        window.location.href = "{{ route('login') }}";
    @endif
}

function addRoom(roomId, roomName, price) {
    if (selectedRooms.find(r => r.id == roomId)) return; // avoid duplicate
    selectedRooms.push({id: roomId, name: roomName, price: parseFloat(price)});

    let container = document.getElementById('selectedRoomsContainer');
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'room_id[]';
    input.value = roomId;
    input.id = 'roomHidden' + roomId;
    container.appendChild(input);
}

function removeRoom(roomId) {
    selectedRooms = selectedRooms.filter(r => r.id != roomId);
    let input = document.getElementById('roomHidden' + roomId);
    if (input) input.remove();

    let checkIcon = document.getElementById('roomCheck' + roomId);
    if (checkIcon) checkIcon.classList.add('d-none');
    let checkbox = document.getElementById('flckDefault' + roomId);
    if (checkbox) checkbox.checked = false;

    updateSummary();
}

function updateSummary() {
    let summaryBody = document.getElementById('summaryRooms');
    let summarySubtotal = document.getElementById('summarySubtotal');
    let summaryTax = document.getElementById('summaryTax');
    let summaryTotal = document.getElementById('summaryTotal');
    let total_price = document.getElementById('total_price');
    let taxInput = document.getElementById('tax_persent');
    let summaryDiv = document.getElementById('bookingSummary');

    summaryBody.innerHTML = '';
    let subtotal = 0;

    if (selectedRooms.length === 0) {
        summaryDiv.classList.add('d-none');
        summarySubtotal.innerText = "₹0";
        summaryTax.innerText = "₹0";
        summaryTotal.innerText = "₹0";
        if (total_price) total_price.value = 0;
        return;
    }

    selectedRooms.forEach((r, index) => {
        subtotal += r.price;
        summaryBody.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${r.name}</td>
                <td class="text-end">₹${r.price.toLocaleString()}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRoom('${r.id}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    // Tax calculation
    let taxPercent = parseFloat(taxInput?.value) || 0;
    let taxAmount = Math.round((subtotal * taxPercent) / 100);
    let total = subtotal + taxAmount;

    // Update display
    summarySubtotal.innerText = "₹" + subtotal.toLocaleString();
    summaryTax.innerText = "₹" + taxAmount.toLocaleString();
    document.getElementById("taxPercent").innerText = taxPercent;
    summaryTotal.innerText = "₹" + total.toLocaleString();

    // Save raw total in hidden input
    if (total_price) total_price.value = total;

    summaryDiv.classList.remove('d-none');
}

</script>
<script>
  let url_u = "{{ route('getAvailableRooms') }}";
  const BASE_URL = "{{ url('/public') }}";

$('#filterBtn').on('click', function () {
    let date       = $('#filter_date').val();
    let in_time    = formatTo24Hr($('#filter_in_time').val());
    let out_time   = formatTo24Hr($('#filter_out_time').val());
    let listing_id = '{{ $listing->id }}';

    $.ajax({
        url: url_u,
        type: 'GET',
        data: { date, in_time, out_time, listing_id },
        success: function (res) {
            let rooms = res.rooms ?? [];
            let roomsHtml = `<div class="row row-28">`;

            rooms.forEach(function (room, key) {
                let firstImage = (room.image && room.image.length > 0) ? room.image[0] : 'default.jpg';

                let featuresHtml = '';
                if (room.features && room.features.length > 0) {
                    featuresHtml = '<div class="d-flex flex-wrap gap-2 mt-2">';
                    room.features.forEach(function (feature) {
                        featuresHtml += `
                            <div class="text-center" style="width: 80px;">
                                <img src="${BASE_URL}/${feature.image ?? 'image/placeholder.png'}"
                                    alt="${feature.name ?? 'Feature'}"
                                    class="rounded mb-1" style="width:30px;height:30px;">
                                <span class="fs-11px d-block" style="font-size: 9px;">${feature.name ?? ''}</span>
                            </div>`;
                    });
                    featuresHtml += '</div>';
                }

                roomsHtml += `
                    <div class="col-sm-12">
                        <input class="form-check-input d-none" name="room[]" type="checkbox" value="${room.id}" id="flckDefault${key}">
                        <label class="form-check-label w-100" 
                            onclick="selectRoom('${key}', '${room.id}', '${room.title}', '${room.price}')"
                            for="flckDefault${key}">

                            <div class="card mb-3 room-checkbox position-relative">
                                <div class="room-check position-absolute top-0 end-0 p-2 d-none" id="roomCheck${key}">
                                    <i class="fas fa-check-circle text-success fs-4"></i>
                                </div>
                                <div class="row g-0 h-100">
                                    <div class="col-md-4">
                                        <img src="${firstImage}" class="img-fluid rounded-start h-40 w-100 object-fit-cover" alt="Room Image">
                                    </div>
                                    <div class="col-md-8 room-body">
                                        <div class="card-body py-2 px-3 h-100 position-relative d-flex flex-column">
                                            <div class="d-flex align-items-center mb-1 flex-wrap">
                                                <p class="card-title mb-0 mr-3 fw-bold line-1">${room.title ?? 'Untitled Room'}</p>
                                                <p class="mb-0 text-success fw-bold">${room.price ?? 0}</p>
                                            </div>
                                            <div class="d-flex gap-2 fs-12px text-muted mb-1">
                                                <span><i class="fas fa-user"></i> ${room.person ?? 0} Persons</span>
                                                <span>|</span>
                                                <span><i class="fas fa-baby"></i> ${room.child ?? 0} Child</span>
                                            </div>
                                            ${room.room_type ? `<p class="mb-1 fs-12px"><strong>Room Type:</strong> ${room.room_type}</p>` : ''}
                                            ${featuresHtml}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>`;
            });
            roomsHtml += `</div>`;
            $('#filter_rooms').html(roomsHtml).show();
            $('.filter_rooms_row').hide();
        }
    });
});

function formatTo24Hr(timeStr) {
    if (!timeStr) return null;
    let parts = timeStr.split(':');
    return parts[0].padStart(2, '0') + ':' + parts[1].padStart(2, '0') + ':00';
}
</script>


@endpush
