@extends('layouts.frontend')
@push('title', get_phrase('Play Grid'))
@push('meta')@endpush
@section('frontend_layout')


    <!-- Start Content Area -->
    <section class="mt-3">
        <div class="container">
            <div class="row row-28 mb-80 mt-3">
                <!-- Sidebar -->
                <div class="col-xl-3 col-lg-4">
                    @include('frontend.play.sidebar_play')
                </div>
                <!-- Right Content Area -->
                <div class="col-xl-9 col-lg-8">
                    <!-- Top Filter Area -->
                    <div class="showing-result-header d-flex align-items-center justify-content-between flex-wrap">
                        <div class="listing-info info">
                            {{ get_phrase('Showing') . '  to ' . count($listings) . ' ' . get_phrase('of') . ' ' . count($listings) . ' ' . get_phrase('results') }}
                        </div>
                        
                    </div>
                    <!-- Google Map -->
                   
                    <div class="row">
                        <!-- Card Area -->
                        <div class="col-xl-12 col-lg-12 order-2 order-xl-1" id="right-map">
                            @if (count($listings) > 0)
                                <div class="row row-28">
                                    @foreach ($listings as $listing)
                                        <!-- Single Card -->
                                        <div class="col-sm-6 {{ get_frontend_settings('map_position') == 'right' ? 'col-sm-6' : 'col-xl-4' }} map-card">
                                            <div class="single-grid-card">
                                                <!-- Banner Slider -->
                                                <div class="grid-slider-area">                             
                                                    <a class="w-100 h-100" href="{{ route('listing.details', ['type' => $type, 'id' => $listing['id'], 'slug' => slugify($listing['title'])]) }}">
                                                        <img class="card-item-image" src="{{ $listing['image'] }}">
                                                    </a>
                                                    <p class="card-light-text theme-light capitalize">{{ $listing['is_popular'] }}</p>                                                   
                                                </div>
                                                <a href="{{ route('listing.details', ['type' => $type, 'id' => $listing['id'], 'slug' => slugify($listing['title'])]) }}" class="play-grid-link ">
                                                    <div class="restaurent-grid-details">
                                                        <div class="restgrid-title-location">
                                                            <h3 class="title"> 
                                                                @if(isset($claimStatus) && $claimStatus['status'] == 1) 
                                                                <span data-bs-toggle="tooltip" 
                                                                data-bs-title=" {{ get_phrase('This listing is verified') }}">
                                                                <svg fill="none" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="paint0_linear_16_1334" gradientUnits="userSpaceOnUse" x1="12" x2="12" y1="-1.2" y2="25.2"><stop offset="0" stop-color="#ce9ffc"/><stop offset=".979167" stop-color="#7367f0"/></linearGradient><path d="m3.783 2.826 8.217-1.826 8.217 1.826c.2221.04936.4207.17297.563.3504.1424.17744.22.39812.22.6256v9.987c-.0001.9877-.244 1.9602-.7101 2.831s-1.14 1.6131-1.9619 2.161l-6.328 4.219-6.328-4.219c-.82173-.5478-1.49554-1.2899-1.96165-2.1605-.46611-.8707-.71011-1.8429-.71035-2.8305v-9.988c.00004-.22748.07764-.44816.21999-.6256.14235-.17743.34095-.30104.56301-.3504zm8.217 10.674 2.939 1.545-.561-3.272 2.377-2.318-3.286-.478-1.469-2.977-1.47 2.977-3.285.478 2.377 2.318-.56 3.272z" fill="url(#paint0_linear_16_1334)"/></svg>
                                                                </span>
                                                                @endif
                                                                {{ $listing['title'] }} </h3>
                                                        </div>
                                                        <div class="restgrid-price-rating d-flex align-items-center justify-content-between">
                                                            <div class="location d-flex">
                                                                <img src="{{ asset('assets/frontend/images/icons/location-red2-20.svg') }}" alt="">                                                                
                                                                <p class="info f-14"> {{ $listing['city'] . ', ' . $listing['country'] }} </p>
                                                            </div>
                                                            <!-- <div class="ratings d-flex align-items-center">
                                                                <img src="{{ asset('assets/frontend/images/icons/star-yellow-16.svg') }}" alt="">                                                               
                                                            </div> -->
                                                        </div>
                                                        <ul class="restgrid-list-items d-flex align-items-center flex-wrap">
                                                            <li>{{ get_phrase('Dine in') }}</li>
                                                            <li>{{ get_phrase('Takeaway') }}</li>
                                                            <li>{{ get_phrase('Delivery') }}</li>
                                                        </ul>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="listing-pegination paginationColor">
                                        {{ $listings->links() }}
                                    </div>
                                </div>
                            @else
                                @include('frontend.no_data')
                            @endif
                        </div>

                        @if (get_frontend_settings('map_position') == 'right')
                            <!-- Map Area -->
                            <div class="col-xl-4 col-lg-12 order-1 order-xl-2">
                                @include('frontend.map')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Content Area -->
@endsection
@push('js')
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
                        type: 'play',
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

@endpush
