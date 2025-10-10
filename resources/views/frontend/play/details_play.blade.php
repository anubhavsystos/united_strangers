@extends('layouts.frontend')
@push('title', get_phrase('Play Listing Details'))
@push('meta')@endpush
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/mapbox-gl.css') }}">
    <script src="{{ asset('assets/frontend/js/mapbox-gl.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/flatpickr.min.css') }}">
    <script src="{{ asset('assets/frontend/js/flatpickr.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/venobox.min.css') }}">
    <script src="{{ asset('assets/frontend/js/venobox.min.js') }}"></script>
@endpush
@section('frontend_layout')
<!-- Start Top Title and Back -->
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="details-title-back1 d-flex align-items-start justify-content-between flex-wrap">
                    <div class="detailstop-title-location1">
                        <div class="detailstop-title1 d-flex align-items-center flex-wrap">
                            @php 
                               $claimStatus = App\Models\ClaimedListing::where('listing_id', $listing->id)->where('listing_type', 'play')->first();  
                            @endphp
                            <h1 class="title">
                                @if(isset($claimStatus) && $claimStatus->status == 1) 
                                <span data-bs-toggle="tooltip" 
                                data-bs-title=" {{ get_phrase('This listing is verified') }}">
                                <svg fill="none" height="34" viewBox="0 0 24 24" width="34" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="paint0_linear_16_1334" gradientUnits="userSpaceOnUse" x1="12" x2="12" y1="-1.2" y2="25.2"><stop offset="0" stop-color="#ce9ffc"/><stop offset=".979167" stop-color="#7367f0"/></linearGradient><path d="m3.783 2.826 8.217-1.826 8.217 1.826c.2221.04936.4207.17297.563.3504.1424.17744.22.39812.22.6256v9.987c-.0001.9877-.244 1.9602-.7101 2.831s-1.14 1.6131-1.9619 2.161l-6.328 4.219-6.328-4.219c-.82173-.5478-1.49554-1.2899-1.96165-2.1605-.46611-.8707-.71011-1.8429-.71035-2.8305v-9.988c.00004-.22748.07764-.44816.21999-.6256.14235-.17743.34095-.30104.56301-.3504zm8.217 10.674 2.939 1.545-.561-3.272 2.377-2.318-3.286-.478-1.469-2.977-1.47 2.977-3.285.478 2.377 2.318-.56 3.272z" fill="url(#paint0_linear_16_1334)"/></svg>
                                </span>
                                @endif
                                {{$listing->title}}</h1>
                            <p class="featured capitalize">{{$listing->is_popular}}</p>
                        </div>
                        <div class="location d-flex align-items-center">
                            @php
                            $city_name = App\Models\City::where('id',$listing->city)->first()->name;
                            $country_name = App\Models\Country::where('id',$listing->country)->first()->name;
                          @endphp
                            <img src="{{asset('assets/frontend/images/icons/location-red2-20.svg')}}" alt="">
                             <p>{{$city_name.', '.$country_name}}</p>
                        </div>
                    </div>
                    <div class="detailstop-share-back d-flex align-items-center flex-wrap">
                      
                           
                        <a href="{{route('listing.view',['type'=>'play','view'=>'grid'])}}" class="back-btn1">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.9752 15.6834C7.81686 15.6834 7.65853 15.6251 7.53353 15.5001L2.4752 10.4418C2.23353 10.2001 2.23353 9.8001 2.4752 9.55843L7.53353 4.5001C7.7752 4.25843 8.1752 4.25843 8.41686 4.5001C8.65853 4.74176 8.65853 5.14176 8.41686 5.38343L3.8002 10.0001L8.41686 14.6168C8.65853 14.8584 8.65853 15.2584 8.41686 15.5001C8.3002 15.6251 8.13353 15.6834 7.9752 15.6834Z" fill="#7E7E89"/>
                                <path d="M17.0831 10.625H3.05811C2.71644 10.625 2.43311 10.3417 2.43311 10C2.43311 9.65833 2.71644 9.375 3.05811 9.375H17.0831C17.4248 9.375 17.7081 9.65833 17.7081 10C17.7081 10.3417 17.4248 10.625 17.0831 10.625Z" fill="#7E7E89"/>
                            </svg>
                            <span>{{get_phrase('Back to listing')}}</span>    
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Top Title and Back -->

<!-- Start Main Content Area -->
<section>
    <div class="container">
        <div class="row row-28 mb-80px">
            <div class="col-xl-7 col-lg-7">
                <!-- Banners Slider -->
                <div class="swiper resdetails-banner-slider mb-30px">
                    <div class="swiper-wrapper">

                         @foreach (json_decode($listing->image) as $key => $image)
                            <div class="swiper-slide">
                                <div class="atn-slide-banner">
                                    <img src="{{ get_all_image('listing-images/'.$image) }}" alt="">
                                </div>
                            </div>
                        @endforeach
                       
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
                <!-- Description -->
                <div class="at-details-description mb-50px">
                    <h4 class="title mb-16">{{get_phrase('Description')}}</h4>
                    <p class="info mb-16">
                        <span id="short-description" class="d-block">{{ Str::limit($listing->description, 400) }}</span>
                        <span id="full-description" class="d-none">{!! removeScripts($listing->description) !!}</span>
                    </p>

                   @if(strlen($listing->description) > 400)
                        <a href="javascript:void(0);" id="read-more-btn" class="icontext-link-btn" onclick="toggleDescription()">
                            <span>{{get_phrase('Read More')}}</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.33217 8.33306H13.0562L9.52751 4.8044C9.46383 4.7429 9.41304 4.66933 9.37811 4.588C9.34317 4.50666 9.32478 4.41918 9.32401 4.33066C9.32324 4.24214 9.34011 4.15436 9.37363 4.07243C9.40715 3.9905 9.45665 3.91606 9.51924 3.85347C9.58184 3.79087 9.65627 3.74137 9.7382 3.70785C9.82014 3.67433 9.90792 3.65746 9.99644 3.65823C10.085 3.659 10.1724 3.67739 10.2538 3.71233C10.3351 3.74727 10.4087 3.79806 10.4702 3.86173L15.1368 8.5284C15.2618 8.65341 15.332 8.82295 15.332 8.99973C15.332 9.17651 15.2618 9.34604 15.1368 9.47106L10.4702 14.1377C10.3444 14.2592 10.176 14.3264 10.0012 14.3248C9.82644 14.3233 9.65923 14.2532 9.53563 14.1296C9.41202 14.006 9.34191 13.8388 9.34039 13.664C9.33887 13.4892 9.40607 13.3208 9.52751 13.1951L13.0562 9.6664H1.33217C1.15536 9.6664 0.985792 9.59616 0.860768 9.47113C0.735744 9.34611 0.665506 9.17654 0.665506 8.99973C0.665506 8.82292 0.735744 8.65335 0.860768 8.52832C0.985792 8.4033 1.15536 8.33306 1.33217 8.33306Z" fill="#242D3D"></path>
                            </svg>
                        </a>
                       @endif
                </div>
                <!-- Menu -->
                 
                @if(count($menus) != 0)
                <div class="restdetails-menu-wrap mb-50px">
                    <h2 class="in-title3-24px mb-5">{{get_phrase('Menu')}}</h2>
                    <div class="restdetails-menu-items">
                        
                        @foreach (($menus ?? []) as $key => $menu)
                        @php
                            if(!empty($menu->dis_price)) {
                                $menu_price = $menu->dis_price;
                            } else {
                                $menu_price = $menu->price;
                            }
                        @endphp
                        <input class="form-check-input d-none" name="room[]"  type="checkbox"  value="{{ $room['id'] ?? '' }}" id="flckDefault{{ $key }}"
                        @if(!empty($listing->room) && $listing->room !== 'null' && in_array($room['id'] ?? 0, json_decode($listing->room, true) ?? [])) checked @endif>
                        <div class="restdetails-menu-item d-flex" onclick="selectMenu('{{ $key }}', '{{ $menu['id'] ?? '' }}', '{{ $menu['title'] ?? 'Untitled Room' }}', '{{ $menu_price ?? 0 }}')" 
                                    for="flckDefault{{ $key }}">
                            <div class="img">
                                <img src="{{get_all_image('menu/'.$menu->image)}}" alt="">
                            </div>
                            <div class="restdetails-menu-details">
                                <h5 class="name">{{$menu->title}}</h5>
                                <div class="prices d-flex align-items-end">
                                    @if(!empty($menu->dis_price))
                                    <p class="new-price">{{ currency($menu->dis_price) }}</p>
                                    <del class="old-price ">{{ currency($menu->price) }}</del>
                                    @elseif(!empty($menu->price))
                                        <p class="new-price">{{ currency($menu->price) }}</p>
                                    @endif
                                </div> 
                            </div>
                        </div>
                         @endforeach
                        <!-- Menu Item -->  
                    </div>
                </div>
                @endif

                <!-- offer -->
                @if(count($offers) != 0)
                <div class="restdetails-menu-wrap mb-50px">
                    <h2 class="in-title3-24px mb-5">{{get_phrase('offer')}}</h2>
                    <div class="restdetails-menu-items">                        
                        @foreach (($offers ?? []) as $key => $offer)                      
                        <input class="form-check-input d-none" name="room[]"  type="checkbox"  value="{{ $room['id'] ?? '' }}" id="flckDefault{{ $key }}"
                        @if(!empty($listing->room) && $listing->room !== 'null' && in_array($room['id'] ?? 0, json_decode($listing->room, true) ?? [])) checked @endif>
                        <div class="restdetails-menu-item d-flex" onclick="selectOffer('{{ $key }}', '{{ $offer['id'] ?? '' }}', '{{ $offer['title'] ?? 'Untitled Room' }}', '{{ $offer['offer_persent'] ?? 0 }}')" 
                                    for="flckDefault{{ $key }}">
                            <div class="img">
                                <img src="{{ $offer['image'] }}" alt="">
                            </div>
                            <div class="restdetails-menu-details">
                                <h5 class="name">{{$offer['title']}}</h5>
                                <div class="prices d-flex align-items-end">
                                    @if(!empty($offer['offer_persent']))
                                        <p class="new-price">{{ $offer['offer_persent'] }} %</p>
                                    @endif
                                </div> 
                            </div>
                        </div>
                         @endforeach
                        <!-- offer Item -->  
                    </div>
                </div>
                @endif

                <!-- event -->
                @if(count($events) != 0)
                <div class="restdetails-menu-wrap mb-50px">
                    <h2 class="in-title3-24px mb-5">{{get_phrase('event')}}</h2>
                    <div class="restdetails-menu-items">                        
                        @foreach (($events ?? []) as $key => $event)                      
                        <input class="form-check-input d-none" name="room[]"  type="checkbox"  value="{{ $room['id'] ?? '' }}" id="flckDefault{{ $key }}"
                        @if(!empty($listing->room) && $listing->room !== 'null' && in_array($room['id'] ?? 0, json_decode($listing->room, true) ?? [])) checked @endif>
                        <div class="restdetails-menu-item d-flex" onclick="selectEvent('{{ $key }}', '{{ $event['id'] ?? '' }}', '{{ $event['title'] ?? 'Untitled Room' }}', '{{ $event['price'] ?? 0 }}', '{{ $event['to_date'] ?? 0 }}')" 
                                    for="flckDefault{{ $key }}">
                            <div class="img">
                                <img src="{{ $event['image'] }}" alt="">
                            </div>
                            <div class="restdetails-menu-details">
                                <h5 class="name">{{$event['title']}}</h5>
                                <div class="prices d-flex align-items-end">
                                    @if(!empty($event['price']))
                                        <p class="new-price">{{ currency($event['price']) }} </p>
                                    @endif
                                </div> 
                                <div class="prices d-flex align-items-end">
                                    @if(!empty($event['to_date']))
                                        <p class="new-price">{{ $event['to_date'] }} </p>
                                    @endif
                                </div> 
                            </div>
                        </div>
                         @endforeach
                        <!-- event Item -->  
                    </div>
                </div>
                @endif
                <!-- Opening Time -->
                <div class="restaurent-opening-time mb-50px">
                    <h2 class="in-title3-24px mb-20">{{get_phrase('Opening Time')}}</h2>
                    @if ($listing->opening_time)
                    <ul class="restopening-time-list">
                       @foreach (json_decode($listing->opening_time) as $key => $day)
                            <li>
                                <p class="day">{{ ucwords($key) }}</p>
                                @if ($day->open === "closed" && $day->close === "closed")
                                    <p class="opening-time-close">{{get_phrase('Closed')}}</p>
                                @else
                                    <p class="time">{{ format_time($day->open) }} - {{ format_time($day->close) }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    @endif
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
            <!-- Right Sidebar -->
             <div class="col-xl-5 col-lg-5">
                    <div class="sleepdetails-form-area mb-30px">
                    <h4 class="sub-title ">{{ get_phrase('Booking') }}</h4>                    
                    <form action="{{ route('customerBookAppointment') }}" method="post">
                        @csrf
                        <input type="hidden" name="listing_type" value="play">
                        <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                        <input type="hidden" name="customer_id" value="{{ $listing->id }}">
                        <input type="hidden" name="title" value="{{ $listing->title }}">
                        <input type="hidden" name="menu_summary" id="menu_summary">
                        <input type="hidden" id="offers_ids" name="offers_ids" value="">
                        <input type="hidden" id="offer_percent" name="offer_percent" value="">
                        <div id="selectedRoomsContainer"></div>
                        <div class="sleepdetails-form-inputs mb-16">
                        <div class="mb-3">
                            <label class="form-label">Appointment Date</label>
                            <input type="date" class="appointmentDate form-control mform-control flat-input-picker3 " name="date" required>
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
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="message" id="message" cols="30" rows="3" placeholder="{{ get_phrase('Write your description') }}" class="form-control"></textarea>                        
                        </div>
                        <input type="hidden" id="tax_persent" value="{{ isset($listing->tax_persent) ? $listing->tax_persent : 0 }}">
                        <input type="hidden" id="total_price" name="total_price" value="0">
                        <div id="bookingSummary" class="card d-none mb-4 mt-3">
                            <div class="card-header fw-bold">{{ get_phrase('Booking Summary') }}</div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ get_phrase('Item') }}</th>
                                            <th class="text-end">{{ get_phrase('Rate') }}</th>
                                            <th class="text-center">{{ get_phrase('Qty') }}</th>
                                            <th class="text-end">{{ get_phrase('Subtotal') }}</th>
                                            <th class="text-center">{{ get_phrase('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="summaryRooms"></tbody>
                                   <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">{{ get_phrase('Subtotal') }}</th>
                                            <th class="text-end" id="summarySubtotal">₹0</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-end">{{ get_phrase('Tax') }} (<span id="summaryTaxPercent">0</span>%)</th>
                                            <th class="text-end" id="summaryTax">₹0</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-end">{{ get_phrase('Grand Total') }}</th>
                                            <th class="text-end" id="summaryGrandTotal">₹0</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>

                        <button type="submit" class="submit-fluid-btn">
                        {{ get_phrase('Proceed Booking') }}
                        </button>
                    </form>
                    </div>
                </div>
        </div>
        <div class="hoteldetails-location-area mb-50px">
                <h2 class="in-title3-24px mb-20px">{{ get_phrase('Location Nearby') }}</h2>                       
                @include('frontend.work.nearby')
            </div>
    </div>
</section>
<!-- End Main Content Area -->
 @php 
$relatedListing = App\Models\PlayListing::where('is_popular', $listing->is_popular)->where('id', '!=', $listing->id)->take(4)->get();
@endphp
 <!-- Start Related Product Area -->
  @if(count($relatedListing) != 0)
 <section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="related-product-title mb-20">{{get_phrase('Related Play')}}</h1>
            </div>
        </div>
        <div class="row row-28 mb-80">
            <!-- Single Card -->
           
            @foreach ($relatedListing->sortByDesc('created_at') as $listing)
            <!-- Single Card -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="single-grid-card">
                    <!-- Banner Slider -->
                    <div class="grid-slider-area">
                        @php
                            $images = json_decode($listing->image);
                            $image = isset($images[0]) ? $images[0] : null;
                            $claimStatus = App\Models\ClaimedListing::where('listing_id', $listing->id)->where('listing_type', 'play')->first(); 
                        @endphp
                        <a class="w-100 h-100" href="{{route('listing.details',['type'=>$type, 'id'=>$listing->id, 'slug'=>slugify($listing->title)])}}">
                            <img class="card-item-image" src="{{ get_all_image('listing-images/' . $image) }}">
                        </a>
                        <p class="card-light-text theme-light capitalize">{{$listing->is_popular}}</p>
                        @php
                        $is_in_wishlist = check_wishlist_status($listing->id, $listing->type);
                      @endphp
                      <a href="javascript:void(0);" data-bs-toggle="tooltip" 
                      data-bs-title="{{ $is_in_wishlist ? get_phrase('Remove from Wishlist') : get_phrase('Add to Wishlist') }}"  onclick="updateWishlist(this, '{{ $listing->id }}')" class="grid-list-bookmark white-bookmark {{ $is_in_wishlist ? 'active' : '' }}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.4361 3C12.7326 3.01162 12.0445 3.22023 11.4411 3.60475C10.8378 3.98927 10.3407 4.53609 10 5.18999C9.65929 4.53609 9.16217 3.98927 8.55886 3.60475C7.95554 3.22023 7.26738 3.01162 6.56389 3C5.44243 3.05176 4.38583 3.57288 3.62494 4.44953C2.86404 5.32617 2.4607 6.48707 2.50302 7.67861C2.50302 10.6961 5.49307 13.9917 8.00081 16.2262C8.56072 16.726 9.26864 17 10 17C10.7314 17 11.4393 16.726 11.9992 16.2262C14.5069 13.9917 17.497 10.6961 17.497 7.67861C17.5393 6.48707 17.136 5.32617 16.3751 4.44953C15.6142 3.57288 14.5576 3.05176 13.4361 3Z" fill="#212529"/>
                            </svg>                                                 
                        </a>
                    </div>
                    <a href="{{route('listing.details',['type'=>$type, 'id'=>$listing->id, 'slug'=>slugify($listing->title)])}}" class="play-grid-link">
                        <div class="restaurent-grid-details">
                            <div class="restgrid-title-location">
                                <h3 class="title">
                                    @if(isset($claimStatus) && $claimStatus->status == 1) 
                                    <span data-bs-toggle="tooltip" 
                                    data-bs-title=" {{ get_phrase('This listing is verified') }}">
                                    <svg fill="none" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="paint0_linear_16_1334" gradientUnits="userSpaceOnUse" x1="12" x2="12" y1="-1.2" y2="25.2"><stop offset="0" stop-color="#ce9ffc"/><stop offset=".979167" stop-color="#7367f0"/></linearGradient><path d="m3.783 2.826 8.217-1.826 8.217 1.826c.2221.04936.4207.17297.563.3504.1424.17744.22.39812.22.6256v9.987c-.0001.9877-.244 1.9602-.7101 2.831s-1.14 1.6131-1.9619 2.161l-6.328 4.219-6.328-4.219c-.82173-.5478-1.49554-1.2899-1.96165-2.1605-.46611-.8707-.71011-1.8429-.71035-2.8305v-9.988c.00004-.22748.07764-.44816.21999-.6256.14235-.17743.34095-.30104.56301-.3504zm8.217 10.674 2.939 1.545-.561-3.272 2.377-2.318-3.286-.478-1.469-2.977-1.47 2.977-3.285.478 2.377 2.318-.56 3.272z" fill="url(#paint0_linear_16_1334)"/></svg>
                                    </span>
                                    @endif
                                    {{$listing->title}} </h3>
                            </div>
                            <div class="restgrid-price-rating d-flex align-items-center justify-content-between">
                                <div class="location d-flex">
                                    <img src="{{asset('assets/frontend/images/icons/location-gray-16.svg')}}" alt="">
                                @php
                                    $city_name = App\Models\City::where('id',$listing->city)->first()->name;
                                    $country_name = App\Models\Country::where('id',$listing->country)->first()->name;
                                @endphp
                                <p class="info f-14"> {{$city_name.', '.$country_name}} </p>
                                </div>
                                <div class="ratings d-flex align-items-center">
                                    <img src="{{asset('assets/frontend/images/icons/star-yellow-16.svg')}}" alt="">
                                    @php
                                      $reviews_count = App\Models\Review::where('listing_id', $listing->id)->where('type', 'play')->where('user_id', '!=', $listing->user_id)->count();
                                    @endphp
                                    <p class="rating">({{ $reviews_count }})</p>
                                </div>
                            </div>
                            <ul class="restgrid-list-items d-flex align-items-center flex-wrap">
                                <li>{{get_phrase('Dine in')}}</li>
                                <li>{{get_phrase('Takeaway')}}</li>
                                <li>{{get_phrase('Delivery')}}</li>
                            </ul>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
            <!-- Single Card -->
            
        </div>
    </div>
</section>
@endif
<!-- End Related Product Area -->


@endsection
@push('js')

<script>
    "use strict";
    $('documnet').ready(function(){
     flatpickr("#datetime", {
         enableTime: true,
         dateFormat: "Y-m-d H:i:S", 
         minDate: "today",
     });
    });

 </script>
<script>
    "use strict";
    let adultsCount = 0;
    let childrenCount = 0;

    function updateGuestCount() {
        const totalGuests = adultsCount + childrenCount;
        document.getElementById("guest-count").textContent = totalGuests;

        // Update hidden inputs
        document.getElementById("adults-input").value = adultsCount;
        document.getElementById("children-input").value = childrenCount;
    }

    function updateCount(type, change, event) {
        event.stopPropagation(); // Prevent dropdown from closing
        if (type === "adults") {
            adultsCount = Math.max(0, adultsCount + change); // Ensure it doesn't go below 0
            document.getElementById("adults-count").textContent = adultsCount;
        } else if (type === "children") {
            childrenCount = Math.max(0, childrenCount + change); // Ensure it doesn't go below 0
            document.getElementById("children-count").textContent = childrenCount;
        }
        updateGuestCount();
    }

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
    document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("toggle-amenities");
    const hiddenAmenities = document.querySelectorAll(".hidden-amenity");

    if (toggleButton) {
        toggleButton.addEventListener("click", function () {
            const isHidden = Array.from(hiddenAmenities).some(item => item.style.display === "none" || item.classList.contains("hidden-amenity"));

            if (isHidden) {
                // Show all amenities
                hiddenAmenities.forEach(item => item.style.display = "list-item");
                toggleButton.querySelector("span").textContent = "See Less";
            } else {
                // Hide extra amenities
                hiddenAmenities.forEach(item => item.style.display = "none");
                toggleButton.querySelector("span").textContent = "View More";
            }
        });
    }
});

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
    const popup = new mapboxgl.Popup({ offset: 25, closeButton: false, closeOnClick: false }) 
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
    document.addEventListener('DOMContentLoaded', function () {
        var latitude = "{{ $listing->Latitude }}";
        var longitude = "{{ $listing->Longitude }}";
        var googleMapsUrl = 'https://www.google.com/maps?q=' + latitude + ',' + longitude;
        var linkElement = document.getElementById('dynamicLocation');
        linkElement.href = googleMapsUrl;
        linkElement.target = '_blank'; 
    });
</script>
<script>
// Selected items arrays

let selectedMenus = [];
let selectedOffers = [];
let selectedEvents = [];

// --- Select Menu Item ---
function selectMenu(key, id, name, price) {
    let existing = selectedMenus.find(r => r.id == id);
    if (!existing) {
        selectedMenus.push({ id, name, price: parseFloat(price), qty: 1 });
    }
    updateSummary();
}

// --- Select Offer ---
function selectOffer(key, id, title, percent) {
    let existing = selectedOffers.find(r => r.id == id);
    if (!existing) {
        selectedOffers.push({ id, title, offer_percent: parseFloat(percent) });
    } else {
        // deselect if clicked again
        selectedOffers = selectedOffers.filter(o => o.id != id);
    }
    updateSummary();
}



// --- Change quantity of menu item ---
function changeQty(id, change) {
    let item = selectedMenus.find(r => r.id == id);
    if (item) {
        item.qty = Math.max(1, item.qty + change);
        updateSummary();
    }
}

// --- Remove menu item ---
function removeMenu(id) {
    selectedMenus = selectedMenus.filter(r => r.id != id);
    updateSummary();
}

// --- Update Summary Table ---
function updateSummary() {
    let summaryBody = document.getElementById('summaryRooms');
    let subtotalEl = document.getElementById('summarySubtotal');
    let taxPercentEl = document.getElementById('summaryTaxPercent');
    let taxEl = document.getElementById('summaryTax');
    let grandTotalEl = document.getElementById('summaryGrandTotal');
    let total_price = document.getElementById('total_price');
    let menu_summary = document.getElementById('menu_summary');
    let offers_ids_input = document.getElementById('offers_ids');
    let offer_percent_input = document.getElementById('offer_percent');
    let summaryDiv = document.getElementById('bookingSummary');
    let taxPercent = parseFloat(document.getElementById('tax_persent')?.value) || 0;

    summaryBody.innerHTML = '';
    let menuSubtotal = 0;
    let summary = [];
    let totalOfferPercent = selectedOffers.reduce((acc, o) => acc + o.offer_percent, 0);

    // --- Menu Items ---
    selectedMenus.forEach((r, index) => {
        let itemSubtotal = r.price * r.qty;
        menuSubtotal += itemSubtotal;
        summary.push(r.qty + " " + r.name);

        summaryBody.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${r.name}</td>
                <td class="text-end">₹${r.price.toLocaleString()}</td>
                <td class="text-center">
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="changeQty('${r.id}', -1)">-</button>
                        <span>${r.qty}</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="changeQty('${r.id}', 1)">+</button>
                    </div>
                </td>
                <td class="text-end">₹${itemSubtotal.toLocaleString()}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeMenu('${r.id}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    // --- Offers Discount ---
    let offerDiscount = (menuSubtotal * totalOfferPercent) / 100;

    // --- Totals ---
    let subtotalAfterOffer = menuSubtotal - offerDiscount;
    let taxAmount = (subtotalAfterOffer * taxPercent) / 100;
    let grandTotal = subtotalAfterOffer + taxAmount;

    // --- Update Table ---
    subtotalEl.innerText = "₹" + subtotalAfterOffer.toLocaleString();
    taxPercentEl.innerText = taxPercent;
    taxEl.innerText = "₹" + taxAmount.toLocaleString();
    grandTotalEl.innerText = "₹" + grandTotal.toLocaleString();

    // --- Hidden Inputs ---
    if (total_price) total_price.value = grandTotal;
    if (menu_summary) menu_summary.value = summary.join(", ");
    if (offers_ids_input) offers_ids_input.value = selectedOffers.map(o => o.id).join(",") || "";
    if (offer_percent_input) offer_percent_input.value = totalOfferPercent;
    
    // --- Show or hide summary ---
    if (selectedMenus.length === 0) {
        summaryDiv.classList.add('d-none');
    } else {
        summaryDiv.classList.remove('d-none');
    }
}

</script>

@endpush