
@extends('layouts.frontend')
@section('title', get_phrase('Real Estate Listing Details'))
@push('css')
 <link rel="stylesheet" href="{{ asset('assets/frontend/css/mapbox-gl.css') }}">
    <script src="{{ asset('assets/frontend/js/mapbox-gl.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/magnific-popup.css') }}">
    <script src="{{ asset('assets/frontend/js/jquery.magnific-popup.min.js') }}"></script>



    <link rel="stylesheet" href="{{ asset('assets/frontend/css/plyr.css') }}">
    <script src="{{ asset('assets/frontend/js/plyr.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/venobox.min.css') }}">
    <script src="{{ asset('assets/frontend/js/venobox.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/flatpickr.min.css') }}">
    <script src="{{ asset('assets/frontend/js/flatpickr.min.js') }}"></script>
@endpush
@section('frontend_layout')
    <!-- Start Bread Crumb  -->
    <section class="mt-20px mb-20px">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb"></nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Bread Crumb  -->

    <!-- Start Top Title and Back -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="details-title-back1 d-flex align-items-start justify-content-between flex-wrap">
                        <div class="detailstop-title-location1">
                            <div class="detailstop-title1 d-flex align-items-center flex-wrap">
                                @php 
                                    $claimStatus = App\Models\ClaimedListing::where('listing_id', $listing->id)->where('listing_type', 'work')->first();  
                                @endphp
                                <h1 class="title">
                                    @if(isset($claimStatus) && $claimStatus->status == 1) 
                                    <span data-bs-toggle="tooltip" 
                                    data-bs-title=" {{ get_phrase('This listing is verified') }}">
                                    <svg fill="none" height="34" viewBox="0 0 24 24" width="34" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="paint0_linear_16_1334" gradientUnits="userSpaceOnUse" x1="12" x2="12" y1="-1.2" y2="25.2"><stop offset="0" stop-color="#ce9ffc"/><stop offset=".979167" stop-color="#7367f0"/></linearGradient><path d="m3.783 2.826 8.217-1.826 8.217 1.826c.2221.04936.4207.17297.563.3504.1424.17744.22.39812.22.6256v9.987c-.0001.9877-.244 1.9602-.7101 2.831s-1.14 1.6131-1.9619 2.161l-6.328 4.219-6.328-4.219c-.82173-.5478-1.49554-1.2899-1.96165-2.1605-.46611-.8707-.71011-1.8429-.71035-2.8305v-9.988c.00004-.22748.07764-.44816.21999-.6256.14235-.17743.34095-.30104.56301-.3504zm8.217 10.674 2.939 1.545-.561-3.272 2.377-2.318-3.286-.478-1.469-2.977-1.47 2.977-3.285.478 2.377 2.318-.56 3.272z" fill="url(#paint0_linear_16_1334)"/></svg>
                                    </span>
                                    @endif
                                    {{$listing->title}}</h1>
                            </div>
                            <div class="location d-flex align-items-center">
                                <img src="{{asset('assets/frontend/images/icons/location-sky-blue2-20.svg')}}" alt="">
                                @php
                                $city_name = App\Models\City::where('id',$listing->city)->first()->name;
                                $country_name = App\Models\Country::where('id',$listing->country)->first()->name;
                            @endphp
                               <p class="name fw-medium"> {{$city_name.', '.$country_name}} </p>
                            </div>
                        </div>
                        <div class="detailstop-share-back d-flex align-items-center flex-wrap">
                            @php
                         $is_in_wishlist = check_wishlist_status($listing->id, $listing->type);
                       @endphp                         
                            <a href="{{route('listing.view',['type'=>'work','view'=>'grid'])}}" class="back-btn1">
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
                <div class="col-xl-8 col-lg-7">
                    <!-- Banners -->
                    <div class="realstate-details-banners mb-30px">
                        <div class="banner-top">
                            @foreach (json_decode($listing->image) as $key => $image)
                            @php if ($key >= 1) { break; } @endphp
                             <img class="big-image-view" src="{{get_all_image('listing-images/'.$image)}}" alt="">
                            @endforeach
                        </div>
                        <ul class="realestate-banner-list">
                            @php
                            $images = json_decode($listing->image) ?? [];
                            $imageCount = count($images);
                        @endphp
                        
                        @foreach ($images as $key => $image)
                            @php 
                                if ($key == 0) { continue; } 
                                elseif($key > 3) { break; } 
                            @endphp
                            @if ($key > 0 && $key <= 3)    
                                <li>
                                    <img class="small-image-view" src="{{get_all_image('listing-images/'.$image)}}" alt="">
                                </li>
                            @endif
                        @endforeach
                        @if ($imageCount > 4)
                            <li class="last-child small-image-view">
                                <img src="{{get_all_image('listing-images/'.$images[4])}}" alt="">
                                <a href="javascript:;" class="see-more" data-bs-toggle="modal" data-bs-target="#imageViewModal"> {{get_phrase('View More')}}</a>
                            </li>
                        @endif
                        
                        </ul>
                    </div>
                    <!-- Price  -->
                    <div class="realestate-pricing-area mb-50px">
                        <div class="pricing d-flex align-items-baseline">
                            <p class="info capitalize">{{get_phrase('Price')}}</p>
                            
                            <div class="d-flex">
                                @if(!empty($listing->discount))
                                <h4 class="price">{{currency($listing->discount)}}</h4>
                                <del class="mt-1 ms-2">{{currency($listing->price)}}</del>
                                @elseif(!empty($listing->price))
                                <h4 class="price">{{currency($listing->price)}}</h4>
                                @endif
                            </div>
                        </div>
                        <div class="row row-28">
                            <div class="col-xl-5 col-lg-12 col-md-5">
                                <div class="realestate-property-title">
                                    <h5 class="title">{{get_phrase('Property')}}</h5>
                                    <p class="info">{{get_phrase('ID :')}} {{$listing->property_id}}</p>
                                </div>
                            </div>
                            <div class="col-xl-7 col-lg-12 col-md-7">
                                <ul class="realestate-property-list d-flex align-items-center">
                                    <li>
                                        <img src="{{asset('assets/frontend/images/icons/bed-gray-30.svg')}}" alt="">
                                        <span>{{$listing->bed}} {{get_phrase('Bed')}}</span>
                                    </li>
                                    <li>
                                        <img src=" {{asset('assets/frontend/images/icons/bath-gray-30.svg')}}" alt="">
                                        <span>{{$listing->bath}} {{get_phrase('Bath')}}</span>
                                    </li>
                                    <li>
                                        <img src="{{asset('assets/frontend/images/icons/resize-arrow-gray-30.svg')}}" alt="">
                                        <span>{{$listing->size}} {{get_phrase('sqft')}}</span>
                                    </li>
                                    <li>
                                        <img src="{{asset('assets/frontend/images/icons/car-gray-30.svg')}}" alt="">
                                        <span>{{$listing->garage}} {{get_phrase('Garage')}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="at-details-description mb-50px">
                        <h4 class="title mb-16">{{get_phrase('Description')}}</h4>
                        <p class="info mb-16">
                            <span id="short-description" class="d-block">{{ Str::limit($listing->description, 400) }}</span>
                            <span id="full-description" class="d-none"> {!! removeScripts($listing->description) !!}</span>
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
                    {{-- Shop Addon --}}
                         @if (addon_status('shop') == 1)
                            @php 
                            $shopItems = App\Models\Inventory::where('type', $listing->type)->where('listing_id', $listing->id)->where('availability', 1)->get();
                            $shopCategories = App\Models\InventoryCategory::where('type', $listing->type)->where('listing_id', $listing->id)->get();
                            @endphp
                        @if($shopItems && $shopItems->count() > 0)
                            @include('frontend.shop')
                        @endif
                     @endif
                  {{-- Shop Addon --}}
                    <!-- Details Address -->
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
                    <div class="row row-28 mb-50px">
                        <div class="col-lg-6">
                            <div class="realestate-details-details">
                                <h4 class="title">{{get_phrase('Details')}}</h4>
                                <ul class="realdetails-detailslist">
                                    <li>
                                        <span class="info">{{get_phrase('Property ID')}}</span>
                                        <span class="value">{{$listing->property_id}}</span>
                                    </li>
                                    <li>
                                        <span class="info capitalize">{{get_phrase('Price')}}</span>
                                        <span class="value">
                                            <div class="d-flex">
                                                @if(!empty($listing->discount))
                                                <p class="price">{{currency($listing->discount)}}</p>
                                                @elseif(!empty($listing->price))
                                                <p class="price">{{currency($listing->price)}}</p>
                                                @endif
                                            </div>
                                        </span>
                                    </li>
                                    <li>
                                        <span class="info">{{get_phrase('Property Size')}}</span>
                                        <span class="value">{{$listing->size}} {{get_phrase('Sqft')}}</span>
                                    </li>
                                    <li>
                                        <span class="info">{{get_phrase('Bedrooms')}}</span>
                                        <span class="value">{{$listing->bed}}</span>
                                    </li>
                                    <li>
                                        <span class="info">{{get_phrase('Bathrooms')}}</span>
                                        <span class="value">{{$listing->bath}}</span>
                                    </li>
                                    <li>
                                        <span class="info">{{get_phrase('Garage')}}</span>
                                        <span class="value">{{$listing->garage}}</span>
                                    </li>
                                    <li>
                                        <span class="info">{{get_phrase('Year Build')}}</span>
                                        <span class="value">{{$listing->year}}</span>
                                    </li>
                                    <li>
                                        <span class="info">{{get_phrase('Property Agent')}}</span>
                                        @php 
                                          $agentName = App\Models\User::where('id', $listing->user_id)->first();
                                        @endphp
                                        <span class="value">{{$agentName->name}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="realestate-details-address">
                                <div class="title-btn d-flex align-items-center justify-content-between flex-wrap">
                                    <h4 class="title">{{get_phrase('Address')}}</h4>
                                    <a href="javascript:;" class="get-direction-btn" id="dynamicLocation">{{ get_phrase('Get Direction') }}</a>
                                </div>
                                <ul class="realdetails-detailslist">
                                    <li>
                                        <span class="info">{{get_phrase('Address')}}</span>
                                        <span class="value">{{$listing->address}}</span>
                                    </li>
                                    <li>
                                        <span class="info">{{get_phrase('City')}}</span>
                                        <span class="value">{{$city_name}}</span>
                                    </li>
                                    <li>
                                        <span class="info">{{get_phrase('Country')}}</span>
                                        <span class="value">{{$country_name}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                      <!--  -->
                    </div>
                    
                </div>
                 
                 <div class="col-xl-4 col-lg-5">
                    <div class="sleepdetails-form-area mb-30px">
                    <h4 class="sub-title ">{{ get_phrase('Booking') }}</h4>
                    
                    <form action="{{ route('customerBookAppointment') }}" method="post">
                        @csrf
                        <input type="hidden" name="listing_type" value="work">
                        <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                        <input type="hidden" name="customer_id" value="{{ $listing->id }}">
                        <div id="selectedRoomsContainer"></div>
                        <div class="sleepdetails-form-inputs mb-16">
                        <div class="mb-3">
                            <label class="form-label">Appointment Date</label>
                            <input type="date" class="appointmentDate form-control mform-control flat-input-picker3 " name="date">
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
                                            <th colspan="2" class="text-end">{{ get_phrase('Tax') }} (<span id="summaryTaxPercent">0</span>%)</th>
                                            <th class="text-end" id="summaryTax">₹0</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-end">{{ get_phrase('Grand Total') }}</th>
                                            <th class="text-end" id="summaryGrandTotal">₹0</th>
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
    <!-- End Main Content Area -->

    <!-- Start Related Product Area -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="in-title3-24px mb-20">{{get_phrase('Related Property')}}</h1>
                </div>
            </div>
            <div class="row row-28 mb-80">
                @php 
                $WorkListings = App\Models\WorkListing::take(4)->get();
                @endphp
                <!-- Single Card -->
                @foreach($WorkListings->sortByDesc('created_at') as $listings)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="single-grid-card">
                        <!-- Banner Slider -->
                        <div class="grid-slider-area">
                            @php
                                $images = json_decode($listings->image);
                                $image = isset($images[0]) ? $images[0] : null;
                                $claimStatus = App\Models\ClaimedListing::where('listing_id', $listings->id)->where('listing_type', 'work')->first(); 
                            @endphp
                            <a class="w-100 h-100" href="{{route('listing.details',['type'=>$type, 'id'=>$listings->id, 'slug'=>slugify($listings->title)])}}">
                                <img class="card-item-image" src="{{ get_all_image('listing-images/' . $image) }}">
                            </a>
                            <p class="card-light-text re-dark-light capitalize">{{$listings->status}}</p>
                            @php
                              $is_in_wishlist = check_wishlist_status($listings->id, $listings->type);
                            @endphp
                            <a href="javascript:void(0);" data-bs-toggle="tooltip" 
                            data-bs-title="{{ $is_in_wishlist ? get_phrase('Remove from Wishlist') : get_phrase('Add to Wishlist') }}" onclick="PopuralupdateWishlist(this, '{{ $listings->id }}')"  class="grid-list-bookmark white-bookmark {{ $is_in_wishlist ? 'active' : '' }}">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.4361 3C12.7326 3.01162 12.0445 3.22023 11.4411 3.60475C10.8378 3.98927 10.3407 4.53609 10 5.18999C9.65929 4.53609 9.16217 3.98927 8.55886 3.60475C7.95554 3.22023 7.26738 3.01162 6.56389 3C5.44243 3.05176 4.38583 3.57288 3.62494 4.44953C2.86404 5.32617 2.4607 6.48707 2.50302 7.67861C2.50302 10.6961 5.49307 13.9917 8.00081 16.2262C8.56072 16.726 9.26864 17 10 17C10.7314 17 11.4393 16.726 11.9992 16.2262C14.5069 13.9917 17.497 10.6961 17.497 7.67861C17.5393 6.48707 17.136 5.32617 16.3751 4.44953C15.6142 3.57288 14.5576 3.05176 13.4361 3Z" fill="#44A1ED"/>
                                </svg>                                                  
                            </a>
                        </div>
                        <div class="reals-grid-details position-relative">
                            <div class="location d-flex">
                                @php
                                $city_name = App\Models\City::where('id',$listings->city)->first()->name;
                                $country_name = App\Models\Country::where('id',$listings->country)->first()->name;
                              @endphp
                                <img src="{{asset('assets/frontend/images/icons/location-gray3-16.svg')}}" alt="">
                                <p class="info fw-medium">{{$city_name.', '.$country_name}} </p>
                            </div>
                            <div class="reals-grid-title mb-16">
                                <a href="{{route('listing.details',['type'=>$type, 'id'=>$listings->id, 'slug'=>slugify($listings->title)])}}" class="title">
                                    @if(isset($claimStatus) && $claimStatus->status == 1) 
                                    <span data-bs-toggle="tooltip" 
                                    data-bs-title=" {{ get_phrase('This listing is verified') }}">
                                    <svg fill="none" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="paint0_linear_16_1334" gradientUnits="userSpaceOnUse" x1="12" x2="12" y1="-1.2" y2="25.2"><stop offset="0" stop-color="#ce9ffc"/><stop offset=".979167" stop-color="#7367f0"/></linearGradient><path d="m3.783 2.826 8.217-1.826 8.217 1.826c.2221.04936.4207.17297.563.3504.1424.17744.22.39812.22.6256v9.987c-.0001.9877-.244 1.9602-.7101 2.831s-1.14 1.6131-1.9619 2.161l-6.328 4.219-6.328-4.219c-.82173-.5478-1.49554-1.2899-1.96165-2.1605-.46611-.8707-.71011-1.8429-.71035-2.8305v-9.988c.00004-.22748.07764-.44816.21999-.6256.14235-.17743.34095-.30104.56301-.3504zm8.217 10.674 2.939 1.545-.561-3.272 2.377-2.318-3.286-.478-1.469-2.977-1.47 2.977-3.285.478 2.377 2.318-.56 3.272z" fill="url(#paint0_linear_16_1334)"/></svg>
                                    </span>
                                    @endif
                                    {{$listings->title}} </a>
                                <p class="info">{{substr_replace($listings->description, "...", 50)}}</p>
                            </div>
                            <div class="reals-bed-bath-sqft d-flex align-items-center flex-wrap">
                                <div class="item d-flex align-items-center">
                                    <img src="{{asset('assets/frontend/images/icons/bed-gray-16.svg')}}" alt="">
                                    <p class="total">{{$listings->bed.' '.get_phrase('Bed')}}</p>
                                </div>
                                <div class="item d-flex align-items-center">
                                    <img src="{{asset('assets/frontend/images/icons/bath-gray-16.svg')}}" alt="">
                                    <p class="total">{{$listings->bath.' '.get_phrase('Bath')}}</p>
                                </div>
                                <div class="item d-flex align-items-center">
                                    <img src="{{asset('assets/frontend/images/icons/resize-arrows-gray-16.svg')}}" alt="">
                                    <p class="total">{{$listings->size.' '.get_phrase('sqft')}}</p>
                                </div>
                            </div>
                            <div class="reals-grid-price-see d-flex align-items-center justify-content-between">
                                <div class="prices d-flex">
                                    <p class="new-price">{{currency($listings->price)}}</p>
                                     @if(!empty($listings->discount))
                                       <p class="old-price">{{ currency($listings->discount) }}</p>
                                    @endif
                                </div>
                                <a href="{{route('listing.details',['type'=>$type, 'id'=>$listings->id, 'slug'=>slugify($listings->title)])}}" class="reals-grid-view realsn-grid-view stretched-link">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.6499 9.33752H6.8999C6.5924 9.33752 6.3374 9.08252 6.3374 8.77502C6.3374 8.46752 6.5924 8.21252 6.8999 8.21252H10.6499C10.9574 8.21252 11.2124 8.46752 11.2124 8.77502C11.2124 9.08252 10.9574 9.33752 10.6499 9.33752Z" fill="#555558"/>
                                        <path d="M8.7749 11.2125C8.4674 11.2125 8.2124 10.9575 8.2124 10.65V6.90002C8.2124 6.59252 8.4674 6.33752 8.7749 6.33752C9.0824 6.33752 9.3374 6.59252 9.3374 6.90002V10.65C9.3374 10.9575 9.0824 11.2125 8.7749 11.2125Z" fill="#555558"/>
                                        <path d="M8.625 16.3125C4.3875 16.3125 0.9375 12.8625 0.9375 8.625C0.9375 4.3875 4.3875 0.9375 8.625 0.9375C12.8625 0.9375 16.3125 4.3875 16.3125 8.625C16.3125 12.8625 12.8625 16.3125 8.625 16.3125ZM8.625 2.0625C5.0025 2.0625 2.0625 5.01 2.0625 8.625C2.0625 12.24 5.0025 15.1875 8.625 15.1875C12.2475 15.1875 15.1875 12.24 15.1875 8.625C15.1875 5.01 12.2475 2.0625 8.625 2.0625Z" fill="#555558"/>
                                        <path d="M16.5001 17.0625C16.3576 17.0625 16.2151 17.01 16.1026 16.8975L13.5003 14.2955C13.2828 14.078 13.2828 13.718 13.5003 13.5005C13.7178 13.283 14.0778 13.283 14.2953 13.5005L16.8976 16.1025C17.1151 16.32 17.1151 16.68 16.8976 16.8975C16.7851 17.01 16.6426 17.0625 16.5001 17.0625Z" fill="#555558"/>
                                    </svg>
                                </a>
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


    <!-- Start Modal Area -->
    <div class="modal modal-main-xl fade" id="imageViewModal" tabindex="-1" aria-labelledby="imageViewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="alm-header-wrap d-flex align-items-center">
                    <div class="alm-header-title-wrap d-flex align-items-center justify-content-between">
                        <h3 class="xl-modal-title">{{$listing->title}}</h3>
                        <div class="alm-rating-review d-flex align-items-center gap-1">
                            <img src="{{asset('assets/frontend/images/icons/star-yellow-16.svg')}}" alt="">
                            <p>({{get_phrase('REVIEWS')}})</p>
                        </div>
                    </div>
                    <ul class="alm-hlist-group align-items-center d-flex">
                        <li class="alm-hlist-item">
                            <svg viewBox="0 0 31 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_164_10032)">
                                <path d="M26.9927 13.9327V5.86933C26.9927 4.2297 25.6128 2.89575 23.9166 2.89575H6.89186C5.19568 2.89575 3.81586 4.2297 3.81586 5.86933V13.9327C1.86141 14.3872 0.404297 16.0902 0.404297 18.117V24.1312C0.404297 24.6084 0.804434 24.9952 1.29803 24.9952H3.8158V27.0847C3.8158 27.5618 4.21594 27.9486 4.70953 27.9486C5.20312 27.9486 5.60326 27.5618 5.60326 27.0847V24.9952H25.2052V27.0847C25.2052 27.5618 25.6054 27.9486 26.0989 27.9486C26.5925 27.9486 26.9927 27.5618 26.9927 27.0847V24.9952H29.5104C30.004 24.9952 30.4042 24.6084 30.4042 24.1312V18.117C30.4043 16.0902 28.9472 14.3872 26.9927 13.9327ZM5.60332 5.86933C5.60332 5.1825 6.18141 4.62369 6.89186 4.62369H23.9167C24.6272 4.62369 25.2053 5.1825 25.2053 5.86933V13.8127H22.2519V12.4497C22.2519 10.1915 20.3512 8.35427 18.0152 8.35427H12.7935C10.4574 8.35427 8.55686 10.1915 8.55686 12.4497V13.8127H5.60332V5.86933ZM20.4644 12.4497V13.8127H10.3442V12.4497C10.3442 11.1442 11.4429 10.0821 12.7934 10.0821H18.0151C19.3656 10.0821 20.4644 11.1443 20.4644 12.4497ZM2.19176 23.2673V18.117C2.19176 16.6964 3.38736 15.5406 4.85695 15.5406H25.9515C27.4211 15.5406 28.6167 16.6964 28.6167 18.117V23.2673H2.19176Z" fill="#9098A4"></path>
                                </g>
                                <defs>
                                <clipPath id="clip0_164_10032">
                                <rect width="30" height="29" fill="white" transform="translate(0.404297 0.922119)"></rect>
                                </clipPath>
                                </defs>
                            </svg>
                            <p class="alm-hlist-totalitem">{{$listing->bed}}</p>
                            <h4 class="alm-hlist-title">{{get_phrase('Bedrooms')}}</h4>
                        </li>
                        <li class="alm-hlist-item">
                            <svg viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_164_10027)">
                                <path d="M27.3978 14.7191H13.8325C10.7296 14.7191 7.70958 14.1262 4.83884 12.956V4.0022C4.83884 3.16756 5.49596 2.48853 6.30368 2.48853H7.47555C8.28327 2.48853 8.9404 3.16756 8.9404 4.0022V4.43184C7.5894 4.82824 6.59665 6.11365 6.59665 7.63501V8.84595C6.59665 9.34752 6.99016 9.75415 7.47555 9.75415H12.1631C12.6484 9.75415 13.042 9.34752 13.042 8.84595V7.63501C13.042 6.11365 12.0492 4.8283 10.6982 4.43184V4.0022C10.6982 2.16599 9.25253 0.672119 7.47555 0.672119H6.30368C4.52671 0.672119 3.08102 2.16599 3.08102 4.0022V12.3147C1.96956 12.1879 0.856395 12.8055 0.37909 13.9149C0.0930352 14.5798 0.074754 15.3201 0.327469 15.9994C0.580184 16.6789 1.07378 17.2158 1.71731 17.5114C1.78124 17.5408 1.84546 17.5693 1.9095 17.5982V21.0764C1.9095 23.8836 3.28616 26.3645 5.37831 27.8368L3.81491 30.26C3.54561 30.6774 3.65477 31.2413 4.05866 31.5195C4.20854 31.6227 4.37782 31.6721 4.5454 31.6721C4.8294 31.6721 5.10813 31.5301 5.27747 31.2675L6.94059 28.6896C7.83327 29.0513 8.80434 29.2502 9.8196 29.2502H20.3665C21.3817 29.2502 22.3528 29.0513 23.2455 28.6896L24.9087 31.2675C25.0781 31.5301 25.3567 31.6721 25.6407 31.6721C25.8082 31.6721 25.9776 31.6227 26.1275 31.5195C26.5314 31.2413 26.6405 30.6774 26.3712 30.26L24.8078 27.8368C26.9 26.3645 28.2767 23.8836 28.2767 21.0765V20.0121C29.2997 19.6372 30.0345 18.6277 30.0345 17.4437C30.0345 15.9413 28.8517 14.7191 27.3978 14.7191ZM9.8193 6.12134C10.627 6.12134 11.2841 6.80037 11.2841 7.63501V7.93774H8.35446V7.63501C8.35446 6.80037 9.01159 6.12134 9.8193 6.12134ZM26.5189 21.0764C26.5189 24.5819 23.7589 27.4338 20.3665 27.4338H9.81966C6.42725 27.4338 3.66731 24.5819 3.66731 21.0764V18.3241C6.92559 19.5484 10.3356 20.1683 13.8325 20.1683H26.5189V21.0764ZM27.3978 18.3519H13.8325C9.87884 18.3519 6.04294 17.5106 2.43128 15.8516C1.98848 15.6483 1.78845 15.1104 1.98538 14.6528C2.13063 14.315 2.45307 14.1141 2.79028 14.1141C2.90923 14.1141 3.03005 14.1391 3.14548 14.192C6.53108 15.747 10.1267 16.5355 13.8325 16.5355H27.3978C27.8824 16.5355 28.2767 16.9429 28.2767 17.4437C28.2767 17.9444 27.8824 18.3519 27.3978 18.3519Z" fill="#9098A4"></path>
                                </g>
                                <defs>
                                <clipPath id="clip0_164_10027">
                                <rect width="30" height="31" fill="white" transform="translate(0.0917969 0.672119)"></rect>
                                </clipPath>
                                </defs>
                            </svg>
                            <p class="alm-hlist-totalitem">{{$listing->bath}}</p>
                            <h4 class="alm-hlist-title">{{get_phrase('Bathrooms')}}</h4>
                        </li>
                        <li class="alm-hlist-item">
                            <svg height="20" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.940828 11.0985C0.873827 11.0036 0.826492 10.8965 0.801568 10.7833C0.776645 10.6701 0.774629 10.5532 0.795639 10.4392C0.816649 10.3253 0.860266 10.2166 0.92396 10.1195C0.987653 10.0224 1.07015 9.93876 1.16668 9.87352L12.8703 1.86902C13.7306 1.29416 14.7443 0.987061 15.7816 0.987061C16.819 0.987061 17.8327 1.29416 18.693 1.86902L30.3966 9.87352C30.5894 10.0053 30.7215 10.2077 30.7639 10.4361C30.8062 10.6645 30.7554 10.9003 30.6224 11.0915C30.4895 11.2827 30.2855 11.4138 30.0552 11.4558C29.8249 11.4978 29.5872 11.4473 29.3944 11.3155L17.6943 3.31627C17.1297 2.9389 16.4643 2.7373 15.7834 2.7373C15.1025 2.7373 14.4371 2.9389 13.8725 3.31627L2.16889 11.3225C2.07345 11.3879 1.96596 11.4339 1.85257 11.458C1.73917 11.4822 1.62209 11.4839 1.50803 11.4631C1.39397 11.4423 1.28516 11.3994 1.18784 11.3368C1.09051 11.2742 1.00657 11.1933 0.940828 11.0985ZM30.7812 29.8515C30.7812 30.0836 30.6883 30.3061 30.5229 30.4702C30.3574 30.6343 30.133 30.7265 29.899 30.7265H1.66778C1.4338 30.7265 1.2094 30.6343 1.04395 30.4702C0.878505 30.3061 0.785556 30.0836 0.785556 29.8515C0.785556 29.6195 0.878505 29.3969 1.04395 29.2328C1.2094 29.0687 1.4338 28.9765 1.66778 28.9765H5.19669V27.0655C4.68243 26.8852 4.23691 26.5517 3.92107 26.1106C3.60523 25.6696 3.4345 25.1425 3.43224 24.6015V21.826C3.43134 21.4814 3.49934 21.14 3.6323 20.8216C3.76525 20.5032 3.96053 20.2141 4.20683 19.971L4.83145 19.3515L3.68985 18.221C3.60782 18.1397 3.54275 18.0431 3.49836 17.9368C3.45397 17.8305 3.43112 17.7166 3.43112 17.6015C3.43112 17.4865 3.45397 17.3725 3.49836 17.2663C3.54275 17.16 3.60782 17.0634 3.68985 16.982C3.8555 16.8177 4.08018 16.7254 4.31446 16.7254C4.43046 16.7254 4.54533 16.7481 4.6525 16.7921C4.75967 16.8361 4.85705 16.9007 4.93908 16.982L5.83718 17.8745L8.33036 12.9273C8.54912 12.4903 8.88687 12.1228 9.30536 11.8664C9.72385 11.61 10.2064 11.475 10.6983 11.4765H20.8686C21.3604 11.475 21.843 11.61 22.2614 11.8664C22.6799 12.1228 23.0177 12.4903 23.2365 12.9273L25.7296 17.8745L26.6277 16.982C26.7934 16.8177 27.0181 16.7254 27.2523 16.7254C27.4866 16.7254 27.7113 16.8177 27.877 16.982C28.0426 17.1463 28.1357 17.3692 28.1357 17.6015C28.1357 17.8339 28.0426 18.0567 27.877 18.221L26.7354 19.3515L27.36 19.971C27.6063 20.2141 27.8016 20.5032 27.9345 20.8216C28.0675 21.14 28.1355 21.4814 28.1346 21.826V24.6015C28.1323 25.1425 27.9616 25.6696 27.6457 26.1106C27.3299 26.5517 26.8844 26.8852 26.3701 27.0655V28.9765H29.899C30.133 28.9765 30.3574 29.0687 30.5229 29.2328C30.6883 29.3969 30.7812 29.6195 30.7812 29.8515ZM26.3701 21.826C26.3701 21.5944 26.2774 21.3723 26.1125 21.2083L25.1226 20.2265H6.44416L5.4543 21.2083C5.28938 21.3723 5.19674 21.5944 5.19669 21.826V24.6015C5.19669 24.8336 5.28964 25.0561 5.45509 25.2202C5.62054 25.3843 5.84493 25.4765 6.07891 25.4765H25.4879C25.7219 25.4765 25.9463 25.3843 26.1117 25.2202C26.2772 25.0561 26.3701 24.8336 26.3701 24.6015V21.826ZM7.50636 18.4765H24.0604L21.6573 13.7095C21.5839 13.5644 21.4713 13.4423 21.332 13.357C21.1927 13.2717 21.0323 13.2265 20.8686 13.2265H10.6983C10.5345 13.2265 10.3741 13.2717 10.2348 13.357C10.0955 13.4423 9.98288 13.5644 9.90954 13.7095L7.50636 18.4765ZM10.49 28.9765V27.2265H6.96114V28.9765H10.49ZM19.3123 28.9765V27.2265H12.2545V28.9765H19.3123ZM24.6057 28.9765V27.2265H21.0768V28.9765H24.6057ZM9.60782 21.9765H7.84337C7.60939 21.9765 7.38499 22.0687 7.21954 22.2328C7.05409 22.3969 6.96114 22.6195 6.96114 22.8515C6.96114 23.0836 7.05409 23.3061 7.21954 23.4702C7.38499 23.6343 7.60939 23.7265 7.84337 23.7265H9.60782C9.8418 23.7265 10.0662 23.6343 10.2316 23.4702C10.3971 23.3061 10.49 23.0836 10.49 22.8515C10.49 22.6195 10.3971 22.3969 10.2316 22.2328C10.0662 22.0687 9.8418 21.9765 9.60782 21.9765ZM23.7234 21.9765H21.959C21.725 21.9765 21.5006 22.0687 21.3352 22.2328C21.1697 22.3969 21.0768 22.6195 21.0768 22.8515C21.0768 23.0836 21.1697 23.3061 21.3352 23.4702C21.5006 23.6343 21.725 23.7265 21.959 23.7265H23.7234C23.9574 23.7265 24.1818 23.6343 24.3473 23.4702C24.5127 23.3061 24.6057 23.0836 24.6057 22.8515C24.6057 22.6195 24.5127 22.3969 24.3473 22.2328C24.1818 22.0687 23.9574 21.9765 23.7234 21.9765Z" fill="#9098A4"></path>
                            </svg>
                            <p class="alm-hlist-totalitem">{{$listing->garage}}</p>
                            <h4 class="alm-hlist-title">{{get_phrase('Garage')}}</h4>
                        </li>
                        <li class="alm-hlist-item">
                            <svg viewBox="0 0 35 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d_164_10038)">
                                <path d="M25.1495 0.0107422H9.28409C7.98799 0.0130022 6.74563 0.528876 5.82915 1.44536C4.91267 2.36184 4.39679 3.6042 4.39453 4.9003V20.7657C4.39679 22.0618 4.91267 23.3042 5.82915 24.2207C6.74563 25.1371 7.98799 25.653 9.28409 25.6553H25.1495C26.4456 25.653 27.688 25.1371 28.6044 24.2207C29.5209 23.3042 30.0368 22.0618 30.0391 20.7657V4.9003C30.0368 3.6042 29.5209 2.36184 28.6044 1.44536C27.688 0.528876 26.4456 0.0130022 25.1495 0.0107422ZM28.3294 20.7657C28.3272 21.6084 27.9914 22.4159 27.3956 23.0118C26.7997 23.6076 25.9922 23.9434 25.1495 23.9456H9.28409C8.44141 23.9434 7.6339 23.6076 7.03804 23.0118C6.44217 22.4159 6.10642 21.6084 6.10417 20.7657V4.9003C6.10642 4.05763 6.44217 3.25011 7.03804 2.65425C7.6339 2.05838 8.44141 1.72263 9.28409 1.72038H25.1495C25.9922 1.72263 26.7997 2.05838 27.3956 2.65425C27.9914 3.25011 28.3272 4.05763 28.3294 4.9003V20.7657Z" fill="#9098A4"></path>
                                <path d="M24.9095 14.406C24.6828 14.406 24.4654 14.496 24.3051 14.6563C24.1447 14.8166 24.0547 15.0341 24.0547 15.2608V18.4663L11.5829 5.99455H14.7885C15.0152 5.99455 15.2326 5.90449 15.3929 5.74418C15.5532 5.58387 15.6433 5.36644 15.6433 5.13973C15.6433 4.91302 15.5532 4.69559 15.3929 4.53528C15.2326 4.37497 15.0152 4.28491 14.7885 4.28491H9.25779C9.10136 4.28491 8.95134 4.34705 8.84072 4.45767C8.73011 4.56828 8.66797 4.71831 8.66797 4.87474V10.4054C8.66797 10.6321 8.75803 10.8495 8.91834 11.0099C9.07865 11.1702 9.29607 11.2602 9.52279 11.2602C9.7495 11.2602 9.96692 11.1702 10.1272 11.0099C10.2875 10.8495 10.3776 10.6321 10.3776 10.4054V7.19984L22.8494 19.6716H19.6438C19.4171 19.6716 19.1997 19.7617 19.0394 19.922C18.8791 20.0823 18.789 20.2997 18.789 20.5264C18.789 20.7532 18.8791 20.9706 19.0394 21.1309C19.1997 21.2912 19.4171 21.3813 19.6438 21.3813H25.1745C25.3309 21.3813 25.481 21.3191 25.5916 21.2085C25.7022 21.0979 25.7643 20.9479 25.7643 20.7914V15.2608C25.7643 15.0341 25.6743 14.8166 25.514 14.6563C25.3536 14.496 25.1362 14.406 24.9095 14.406Z" fill="#9098A4"></path>
                                </g>
                                <defs>
                                <filter id="filter0_d_164_10038" x="0.394531" y="0.0107422" width="33.6445" height="33.6445" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
                                    <feOffset dy="4"></feOffset>
                                    <feGaussianBlur stdDeviation="2"></feGaussianBlur>
                                    <feComposite in2="hardAlpha" operator="out"></feComposite>
                                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"></feColorMatrix>
                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_164_10038"></feBlend>
                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_164_10038" result="shape"></feBlend>
                                </filter>
                                </defs>
                            </svg>
                            <p class="alm-hlist-totalitem">{{$listing->size}}</p>
                            <h4 class="alm-hlist-title">{{get_phrase('sqft')}}</h4>
                        </li>
                        <li class="alm-hlist-item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_164_1319)">
                                <path d="M15.5299 1.87337H14.7383V1.08171C14.7383 0.871743 14.6549 0.670379 14.5064 0.521913C14.3579 0.373447 14.1566 0.290039 13.9466 0.290039C13.7367 0.290039 13.5353 0.373447 13.3868 0.521913C13.2384 0.670379 13.1549 0.871743 13.1549 1.08171V1.87337H6.82161V1.08171C6.82161 0.871743 6.73821 0.670379 6.58974 0.521913C6.44127 0.373447 6.23991 0.290039 6.02995 0.290039C5.81998 0.290039 5.61862 0.373447 5.47016 0.521913C5.32169 0.670379 5.23828 0.871743 5.23828 1.08171V1.87337H4.44661C3.39719 1.87463 2.3911 2.29207 1.64904 3.03413C0.906979 3.77619 0.489538 4.78228 0.488281 5.83171L0.488281 15.3317C0.489538 16.3811 0.906979 17.3872 1.64904 18.1293C2.3911 18.8713 3.39719 19.2888 4.44661 19.29H15.5299C16.5794 19.2888 17.5855 18.8713 18.3275 18.1293C19.0696 17.3872 19.487 16.3811 19.4883 15.3317V5.83171C19.487 4.78228 19.0696 3.77619 18.3275 3.03413C17.5855 2.29207 16.5794 1.87463 15.5299 1.87337ZM2.07161 5.83171C2.07161 5.20182 2.32184 4.59773 2.76724 4.15233C3.21264 3.70693 3.81673 3.45671 4.44661 3.45671H15.5299C16.1598 3.45671 16.7639 3.70693 17.2093 4.15233C17.6547 4.59773 17.9049 5.20182 17.9049 5.83171V6.62337H2.07161V5.83171ZM15.5299 17.7067H4.44661C3.81673 17.7067 3.21264 17.4565 2.76724 17.0111C2.32184 16.5657 2.07161 15.9616 2.07161 15.3317V8.20671H17.9049V15.3317C17.9049 15.9616 17.6547 16.5657 17.2093 17.0111C16.7639 17.4565 16.1598 17.7067 15.5299 17.7067Z" fill="#9098A4"></path>
                                <path d="M9.98828 13.3525C10.6441 13.3525 11.1758 12.8209 11.1758 12.165C11.1758 11.5092 10.6441 10.9775 9.98828 10.9775C9.33244 10.9775 8.80078 11.5092 8.80078 12.165C8.80078 12.8209 9.33244 13.3525 9.98828 13.3525Z" fill="#9098A4"></path>
                                <path d="M6.03027 13.3525C6.68611 13.3525 7.21777 12.8209 7.21777 12.165C7.21777 11.5092 6.68611 10.9775 6.03027 10.9775C5.37444 10.9775 4.84277 11.5092 4.84277 12.165C4.84277 12.8209 5.37444 13.3525 6.03027 13.3525Z" fill="#9098A4"></path>
                                <path d="M13.9463 13.3525C14.6021 13.3525 15.1338 12.8209 15.1338 12.165C15.1338 11.5092 14.6021 10.9775 13.9463 10.9775C13.2905 10.9775 12.7588 11.5092 12.7588 12.165C12.7588 12.8209 13.2905 13.3525 13.9463 13.3525Z" fill="#9098A4"></path>
                               </g>
                               <defs>
                               <clipPath id="clip0_164_1319">
                               <rect width="19" height="19" fill="white" transform="translate(0.488281 0.290039)"></rect>
                               </clipPath>
                               </defs>
                            </svg>
                            <p class="alm-hlist-totalitem">{{$listing->year}}</p>
                            <h4 class="alm-hlist-title">{{get_phrase('Year Build')}}</h4>
                        </li>
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row mt-2 gx-3 row-gap-3">

                     @foreach (json_decode($listing->image) as $key => $image)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="sing-gallery">
                                <div class="gallery-head">
                                    <a class="veno-gallery-img" href="{{get_all_image('listing-images/'.$image)}}"><img src="{{get_all_image('listing-images/'.$image)}}" alt=""></a>
                                </div>
                              
                            </div>
                        </div>
                     @endforeach
                    
                </div>
            </div>
        </div>
        </div>
    </div>

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
        document.addEventListener('DOMContentLoaded', function() {
            var latitude = "{{ $listing->Latitude }}";
            var longitude = "{{ $listing->Longitude }}";
            var googleMapsUrl = 'https://www.google.com/maps?q=' + latitude + ',' + longitude;
            var linkElement = document.getElementById('dynamicLocation');
            linkElement.href = googleMapsUrl;
            linkElement.target = '_blank';
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

    // Add hidden input
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

    // Remove hidden input
    let input = document.getElementById('roomHidden' + roomId);
    if (input) input.remove();

    // Remove green check on card
    let checkIcon = document.getElementById('roomCheck' + roomId);
    if (checkIcon) checkIcon.classList.add('d-none');

    // Uncheck checkbox if exists
    let checkbox = document.getElementById('flckDefault' + roomId);
    if (checkbox) checkbox.checked = false;

    updateSummary();
}

function updateSummary() {
    let summaryBody = document.getElementById('summaryRooms');
    let subtotalEl = document.getElementById('summarySubtotal');
    let taxPercentEl = document.getElementById('summaryTaxPercent');
    let taxEl = document.getElementById('summaryTax');
    let grandTotalEl = document.getElementById('summaryGrandTotal');
    let total_price = document.getElementById('total_price');
    let summaryDiv = document.getElementById('bookingSummary');
    let taxPersent = parseFloat(document.getElementById('tax_persent')?.value) || 0;

    summaryBody.innerHTML = '';
    let subtotal = 0;

    if (selectedRooms.length === 0) {
        summaryDiv.classList.add('d-none');
        subtotalEl.innerText = "₹0";
        taxPercentEl.innerText = taxPersent;
        taxEl.innerText = "₹0";
        grandTotalEl.innerText = "₹0";
        if (total_price) total_price.value = 0;
        return;
    }

    // loop through selected rooms
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

    // calculate
    let taxAmount = (subtotal * taxPersent) / 100;
    let grandTotal = subtotal + taxAmount;

    // update footer values
    subtotalEl.innerText = "₹" + subtotal.toLocaleString();
    taxPercentEl.innerText = taxPersent;
    taxEl.innerText = "₹" + taxAmount.toLocaleString();
    grandTotalEl.innerText = "₹" + grandTotal.toLocaleString();

    // hidden input for saving
    if (total_price) total_price.value = grandTotal;

    summaryDiv.classList.remove('d-none');
}



</script>


@endpush