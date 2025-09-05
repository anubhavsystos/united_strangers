@extends('layouts.frontend')
@push('title', get_phrase('sleep Grid'))
@push('meta')@endpush
@section('frontend_layout')

    <!-- Start Content Area -->
    <section class="mt-3">
        <div class="container">
            <div class="row row-28 mb-80">
                <!-- Sidebar -->
                <div class="col-xl-3 col-lg-4">
                    @include('frontend.sleep.sidebar_sleep')
                </div>
                <!-- Right Content Area -->
                <div class="col-xl-9 col-lg-8">
                    <!-- Top Filter Area -->
                    <div class="showing-result-header d-flex align-items-center justify-content-between flex-wrap">
                        <div class="listing-info info">
                            {{ get_phrase('Showing') . '  to ' . count($listings) . ' ' . get_phrase('of') . ' ' . count($listings) . ' ' . get_phrase('results') }}
                        </div>
                    </div>
              
                    <div class="row">
                        <div class="{{ get_frontend_settings('map_position') == 'right' ? 'col-xl-8' : 'col-xl-12' }} col-lg-12 order-2 order-xl-1" id="right-map">
                            <!-- Card Area -->
                            @if (count($listings) > 0)
                                <div class="row row-28">
                                    @foreach ($listings as $listing)
                                        <!-- Single Card -->
                                        <div class="col-sm-6 map-card">
                                            <div class="single-grid-card">
                                                <div class="grid-slider-area">                                                   
                                                    <a class="w-100 h-100" href="{{route('listing.details',['type'=>$type, 'id'=>$listing['id'], 'slug'=>slugify($listing['title'])])}}"><img class="card-item-image" src="{{ $listing['image_url'] }}"></a>
                                                    <p class="card-light-text theme-light capitalize">{{ $listing['is_popular'] }}</p>                                                    
                                                </div>
                                                <div class="sleep-grid-details position-relative">
                                                    <a href="{{ route('listing.details', ['type' => $type, 'id' => $listing['id'], 'slug' => slugify($listing['title'])]) }}" class="title">                                                      
                                                        {{ $listing['title'] }} </a>
                                                    <div class="sleepgrid-location-rating d-flex align-items-center justify-content-between flex-wrap">
                                                        <div class="location d-flex">
                                                            <img src="{{ asset('assets/frontend/images/icons/location-gray-16.svg') }}" alt="">                                                            
                                                            <p class="name"> {{  $listing['city']  . ', ' .  $listing['country']  }} </p>
                                                        </div>                                                        
                                                    </div>                                                    
                                                    <div class="sleepgrid-see-price d-flex align-items-center justify-content-between">
                                                        <a href="{{ route('listing.details', ['type' => $type, 'id' => $listing['id'], 'slug' => slugify($listing['title'])]) }}" class="see-details-btn1 stretched-link">{{ get_phrase('See Details') }}</a>
                                                        <div class="prices d-flex">
                                                            <p class="price">{{ currency($listing['price']) }}</p>
                                                            <p class="time">/{{ get_phrase('night') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
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
@endpush
