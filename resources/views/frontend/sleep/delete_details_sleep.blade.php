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
                            <p class="price">{{ get_phrase('Total Price : ') }}<span>{{ currency($listing->price) }}</span></p>
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
    <!-- End Top Area -->

    <!-- Start Main Content Area -->
    <section>
        <div class="container">
            <div class="row row-28 mb-80px">
                <div class="col-xl-12 col-lg-7">
                    <!-- Banners Slider -->
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
                       
                        <h1 class="title mb-20">
                            
                            {{ $listing->title }}</h1>
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
                                <!-- <img src="{{ asset('assets/frontend/images/icons/star-yellow-20.svg') }}" alt=""> -->
                                <!-- <p>{{ number_format($average_rating, 1) }} ({{ $reviews_count }})</p> -->
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
                            <li>
                                <img src="{{ asset('assets/frontend/images/icons/move-arrow-gray-24.svg') }}" alt="">
                                <span>{{ $listing->size }} {{ get_phrase('sft') }}</span>
                            </li>
                            <li>
                                <img src="{{ asset('assets/frontend/images/icons/move-arrow-gray-24.svg') }}" alt="">
                                <span>{{ $listing->accommodation_type }} {{ get_phrase('Accommodation Type') }}</span>
                            </li>
                        </ul>
                    </div>
                    <!-- Description -->
                    <div class="at-details-description mb-36">
                        <h4 class="title mb-16">{{ get_phrase('Description') }}</h4>
                        <p class="info mb-16">
                            <span id="short-description" class="d-block">{{ Str::limit($listing->description, 400) }}</span>
                            <span id="full-description" class="d-none">{!! removeScripts($listing->description) !!}</span>
                        </p>
                        @if (strlen($listing->description) > 400)
                            <a href="javascript:void(0);" id="read-more-btn" class="icontext-link-btn" onclick="toggleDescription()">
                                <span>{{ get_phrase('Read More') }}</span>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M1.33217 8.33306H13.0562L9.52751 4.8044C9.46383 4.7429 9.41304 4.66933 9.37811 4.588C9.34317 4.50666 9.32478 4.41918 9.32401 4.33066C9.32324 4.24214 9.34011 4.15436 9.37363 4.07243C9.40715 3.9905 9.45665 3.91606 9.51924 3.85347C9.58184 3.79087 9.65627 3.74137 9.7382 3.70785C9.82014 3.67433 9.90792 3.65746 9.99644 3.65823C10.085 3.659 10.1724 3.67739 10.2538 3.71233C10.3351 3.74727 10.4087 3.79806 10.4702 3.86173L15.1368 8.5284C15.2618 8.65341 15.332 8.82295 15.332 8.99973C15.332 9.17651 15.2618 9.34604 15.1368 9.47106L10.4702 14.1377C10.3444 14.2592 10.176 14.3264 10.0012 14.3248C9.82644 14.3233 9.65923 14.2532 9.53563 14.1296C9.41202 14.006 9.34191 13.8388 9.34039 13.664C9.33887 13.4892 9.40607 13.3208 9.52751 13.1951L13.0562 9.6664H1.33217C1.15536 9.6664 0.985792 9.59616 0.860768 9.47113C0.735744 9.34611 0.665506 9.17654 0.665506 8.99973C0.665506 8.82292 0.735744 8.65335 0.860768 8.52832C0.985792 8.4033 1.15536 8.33306 1.33217 8.33306Z"
                                        fill="#242D3D"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    <!-- filter div by person and avable date check in time get data using ajax  -->
                    @php $plus = 1 @endphp
                    <div class="row row-28">
                       @foreach (($rooms ?? []) as $key => $room)
                        <div class="col-sm-6"> 
                            <input class="form-check-input d-none" name="room[]" type="checkbox" value="{{ $room['id'] ?? '' }}" id="flckDefault{{ $key }}" 
                                @if(!empty($listing->room) && $listing->room != 'null' && in_array($room['id'] ?? 0, json_decode($listing->room, true) ?? [])) checked @endif>

                            <label class="form-check-label w-100" onclick="room_select('{{ $key }}')" for="flckDefault{{ $key }}">
                                <div class="card mb-3 eRoom eRoom2 room-checkbox h-100">
                                    <div class="row g-0 h-100">
                                        <div class="col-md-4">
                                            @php
                                                $images = $room['image'] ?? [];
                                                $firstImage = $images[0] ?? 'default.jpg';
                                            @endphp
                                            <img src="{{ $firstImage}}" 
                                                class="img-fluid rounded-start h-100 object-fit-cover" 
                                                alt="Room Image">
                                        </div>

                                        <div class="col-md-8 room-body">
                                            <div class="card-body py-2 px-2 h-100 position-relative d-flex flex-column">                                                
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <p class="card-title mb-0 fw-bold line-1">{{ $room['title'] ?? 'Untitled Room' }} </p>                                                    
                                                </div>                                                
                                                <p class="card-text fs-12px text-success fw-bold">
                                                    {{ !empty($room['price']) ? currency($room['price']) : currency(0) }}
                                                </p>
                                                <p class="mb-1 fs-12px">
                                                    <i class="fas fa-user"></i> {{ $room['person'] ?? 0 }} {{ get_phrase('Persons') }}
                                                    | <i class="fas fa-baby"></i> {{ $room['child'] ?? 0 }} {{ get_phrase('Child') }}
                                                </p>
                                                @if(!empty($room['features']))
                                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                                        @foreach ($room['features'] as $fKey => $feature)
                                                            <div class=" mb-0 team-checkbox text-center" style="width: 120px; height: 100px; position: relative;">
                                                                <div class="card-body p-2 d-flex flex-column justify-content-center align-items-center">
                                                                    <div class="icon mb-1">
                                                                        <img src="{{ asset(!empty($feature['image']) ? '/' . $feature['image'] : '/image/placeholder.png') }}" 
                                                                            alt="{{ $feature['name'] ?? 'Feature' }}" 
                                                                            class="rounded" 
                                                                            style="width:40px;height:40px;">
                                                                    </div>
                                                                    <span class="text-center fs-12px w-100">{{ $feature['name'] ?? '' }}</span>
                                                                </div>
                                                                <div class="checked 
                                                                    @if(!empty($listing->feature) && $listing->feature != 'null' && in_array($feature['id'] ?? 0, json_decode($listing->feature, true) ?? [])) 
                                                                    @else d-none 
                                                                    @endif" 
                                                                    id="feature-checked{{ $fKey }}">
                                                                    <i class="fas fa-check"></i>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @php
                                                    $roomTypes = [];
                                                    if (!empty($room['room_type'])) {
                                                        if (is_array($room['room_type'])) {
                                                            $roomTypes = $room['room_type'];
                                                        } elseif (is_string($room['room_type'])) {
                                                            $decoded = json_decode($room['room_type'], true);
                                                            $roomTypes = is_array($decoded) ? $decoded : array_map('trim', explode(',', $room['room_type']));
                                                        }
                                                    }
                                                @endphp
                                                @if(!empty($roomTypes))
                                                    <p class="mb-1 fs-12px">
                                                        <strong>{{ get_phrase('Room Type') }}:</strong> {{ implode(', ', $roomTypes) }}
                                                    </p>
                                                @endif
                                                <div class="ms-auto">
                                                    <button type="submit" class="submit-fluid-btn">{{ get_phrase('Book Now') }}</button>                                    
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
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
                                @php
                                    $is_in_wishlist = check_wishlist_status($listings->id, $listings->type);
                                @endphp
                                <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-title="{{ $is_in_wishlist ? get_phrase('Remove from Wishlist') : get_phrase('Add to Wishlist') }}" onclick="PopuralupdateWishlist(this, '{{ $listings->id }}')" class="grid-list-bookmark gray-bookmark {{ $is_in_wishlist ? 'active' : '' }}">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.4361 3C12.7326 3.01162 12.0445 3.22023 11.4411 3.60475C10.8378 3.98927 10.3407 4.53609 10 5.18999C9.65929 4.53609 9.16217 3.98927 8.55886 3.60475C7.95554 3.22023 7.26738 3.01162 6.56389 3C5.44243 3.05176 4.38583 3.57288 3.62494 4.44953C2.86404 5.32617 2.4607 6.48707 2.50302 7.67861C2.50302 10.6961 5.49307 13.9917 8.00081 16.2262C8.56072 16.726 9.26864 17 10 17C10.7314 17 11.4393 16.726 11.9992 16.2262C14.5069 13.9917 17.497 10.6961 17.497 7.67861C17.5393 6.48707 17.136 5.32617 16.3751 4.44953C15.6142 3.57288 14.5576 3.05176 13.4361 3Z" fill="#fff" />
                                    </svg>
                                </a>
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
                                    @php
                                        $reviews_count = App\Models\Review::where('listing_id', $listings->id)->where('user_id', '!=', $listings->user_id)->where('type', 'sleep')->where('reply_id', null)->count();
                                        $total_ratings = App\Models\Review::where('listing_id', $listings->id)->where('user_id', '!=', $listings->user_id)->where('type', 'sleep')->where('reply_id', null)->sum('rating');
                                        $average_rating = $reviews_count > 0 ? $total_ratings / $reviews_count : 0;
                                    @endphp
                                    <div class="ratings d-flex align-items-center">
                                        <p class="rating">{{ number_format($average_rating, 1) }}</p>
                                        <img src="{{ asset('assets/frontend/images/icons/star-yellow-20.svg') }}" alt="">
                                        <p class="reviews">({{ $reviews_count }})</p>
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
                <!-- Single Card -->

            </div>
        </div>
    </section>
    <!-- End Related Product Area -->

    

@endsection
@push('js')
    <script>
        "use strict";
        $('documnet').ready(function() {
            flatpickr("#datetime", {
                enableTime: true,
                dateFormat: "Y-m-d H:i:S",
                minDate: "today",
            });
        });
    </script>


    <script>
        "use strict";
        $(document).ready(function() {
            $('#shareButton').on('click', function() {
                var currentPageUrl = window.location.href;
                $(this).toggleClass('active');
                navigator.clipboard.writeText(currentPageUrl).then(function() {
                    success('Successfully copied this link!');
                }).catch(function(error) {
                    error('Failed to copy the link!');
                });
            });
        });
    </script>

    @if (Auth::check())
        <script>
            "use strict";

            function updateWishlist(button, listingId) {
                const bookmarkButton = $(button);
                const isActive = bookmarkButton.hasClass('active');
                bookmarkButton.toggleClass('active');
                const newTooltipText = isActive ? 'Add to Wishlist' : 'Remove from Wishlist';
                bookmarkButton.attr('data-bs-title', newTooltipText);

                const tooltipInstance = bootstrap.Tooltip.getInstance(button);
                if (tooltipInstance) tooltipInstance.dispose();
                new bootstrap.Tooltip(button);

                $.ajax({
                    url: '{{ route('wishlist.update') }}',
                    method: 'POST',
                    data: {
                        listing_id: listingId,
                        type: 'sleep',
                        user_id: {{ auth()->check() ? auth()->id() : 'null' }},
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            success(response.message);
                        } else if (response.status === 'error') {
                            bookmarkButton.toggleClass('active');
                            const revertTooltipText = isActive ? 'Remove from Wishlist' : 'Add to Wishlist';
                            bookmarkButton.attr('data-bs-title', revertTooltipText);
                            const revertTooltipInstance = bootstrap.Tooltip.getInstance(button);
                            if (revertTooltipInstance) revertTooltipInstance.dispose();
                            new bootstrap.Tooltip(button);
                        }
                    },
                    error: function(xhr) {
                        bookmarkButton.toggleClass('active');
                        const revertTooltipText = isActive ? 'Remove from Wishlist' : 'Add to Wishlist';
                        bookmarkButton.attr('data-bs-title', revertTooltipText);
                        const revertTooltipInstance = bootstrap.Tooltip.getInstance(button);
                        if (revertTooltipInstance) revertTooltipInstance.dispose();
                        new bootstrap.Tooltip(button);
                    },
                });
            }
        </script>
    @else
        <script>
            "use strict";

            function updateWishlist(listing_id) {
                warning("Please login first!");
            }
        </script>
    @endif

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

    @if (Auth::check())
        @if (isset(auth()->user()->id) && auth()->user()->id != $listing->user_id)
            <script>
                "use strict";

                function followers(user_id) {
                    $.ajax({
                        url: "{{ route('followUnfollow') }}",
                        method: "POST",
                        data: {
                            agent_id: user_id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status == 1) {
                                $("#followStatus").html('Unfollow');
                                success("Follow Successfully!");
                            } else {
                                $("#followStatus").html('Follow');
                                success("Unfollow Successfully!");
                            }
                        },
                        error: function() {
                            error("An error occurred. Please try again.");
                        }
                    });
                }
            </script>
        @else
            <script>
                "use strict";

                function followers(user_id) {
                    warning("You can't follow yourself!");
                }
            </script>
        @endif
    @else
        <script>
            "use strict";

            function followers(listing_id) {
                warning("Please login first!");
            }
        </script>
    @endif


    @if (Auth::check())
        @if (isset(auth()->user()->id) && auth()->user()->id != $listing->user_id)
            <script>
                "use strict";

                function send_message(user_id) {
                    var message = $('#message').val();
                    if (message != "") {
                        $.ajax({
                            url: '{{ route('customerMessage') }}',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                agent_id: user_id,
                                message: message
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.status == 'success') {
                                    success("Message sent successfully");
                                    $('#message').val('');
                                } else {
                                    error("Message send failed");
                                }
                            }
                        });
                    } else {
                        warning("Please fill up the field first");
                    }
                }
            </script>
        @else
            <script>
                "use strict";

                function send_message(user_id) {
                    warning("You can't Message yourself");
                }
            </script>
        @endif
    @else
        <script>
            "use strict";

            function send_message(listing_id) {
                warning("Please login first!");
            }
        </script>
    @endif



    @if (Auth::check())
        <script>
            "use strict";

            function PopuralupdateWishlist(button, listingId) {
                const bookmarkButton = $(button);
                const isActive = bookmarkButton.hasClass('active');
                bookmarkButton.toggleClass('active');
                const newTooltipText = isActive ? 'Add to Wishlist' : 'Remove from Wishlist';
                bookmarkButton.attr('data-bs-title', newTooltipText);

                const tooltipInstance = bootstrap.Tooltip.getInstance(button);
                if (tooltipInstance) tooltipInstance.dispose();
                new bootstrap.Tooltip(button);

                $.ajax({
                    url: '{{ route('wishlist.update') }}',
                    method: 'POST',
                    data: {
                        listing_id: listingId,
                        type: 'sleep',
                        user_id: {{ auth()->check() ? auth()->id() : 'null' }},
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            success(response.message);
                        } else if (response.status === 'error') {
                            bookmarkButton.toggleClass('active');
                            const revertTooltipText = isActive ? 'Remove from Wishlist' : 'Add to Wishlist';
                            bookmarkButton.attr('data-bs-title', revertTooltipText);
                            const revertTooltipInstance = bootstrap.Tooltip.getInstance(button);
                            if (revertTooltipInstance) revertTooltipInstance.dispose();
                            new bootstrap.Tooltip(button);
                        }
                    },
                    error: function(xhr) {
                        bookmarkButton.toggleClass('active');
                        const revertTooltipText = isActive ? 'Remove from Wishlist' : 'Add to Wishlist';
                        bookmarkButton.attr('data-bs-title', revertTooltipText);
                        const revertTooltipInstance = bootstrap.Tooltip.getInstance(button);
                        if (revertTooltipInstance) revertTooltipInstance.dispose();
                        new bootstrap.Tooltip(button);
                    },
                });
            }
        </script>
    @else
        <script>
            "use strict";

            function PopuralupdateWishlist(listing_id) {
                warning("Please login first!");
            }
        </script>
    @endif


    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function() {
            var latitude = "{{ $listing->Latitude }}";
            var longitude = "{{ $listing->Longitude }}";
            var googleMapsUrl = 'https://www.google.com/maps?q=' + latitude + ',' + longitude;
            var linkElement = document.getElementById('dynamicLocation');
            linkElement.href = googleMapsUrl;
            linkElement.target = '_blank';
        });
    </script>


@endpush
