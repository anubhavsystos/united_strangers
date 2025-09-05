@extends('layouts.frontend')
@push('title', get_phrase('Real Estate Grid'))
@push('meta')@endpush
@section('frontend_layout')

 <!-- Start Content Area -->
 <section>
    <div class="container">
        <div class="row row-28 mb-80 mt-3">
            <!-- Sidebar -->
            <div class="col-xl-3 col-lg-4">
                @include('frontend.work.sidebar_work')
            </div>
            <!-- Right Content Area -->
            <div class="col-xl-9 col-lg-8">
                <!-- Top Filter Area -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="showing-result-header d-flex align-items-center justify-content-between flex-wrap">
                            <div class="listing-info info">                              
                                    {{get_phrase('Showing').'  to '.count($listings).' '.get_phrase('of').' '.count($listings).' '.get_phrase('results')}}                               
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Card Area -->
                    <div class="{{get_frontend_settings('map_position') == 'right'?'col-xl-8':'col-xl-12'}} col-lg-12 order-2 order-xl-1" id="right-map">
                        @if(count($listings) > 0)
                        <div class="row row-28">
                            @foreach ($listings as $listing)
                            <div class="col-sm-4  map-card">
                                <div class="single-grid-card">
                                    <div class="grid-slider-area">                                        
                                        <a class="w-100 h-100" href="{{route('listing.details',['type'=>$type, 'id'=>$listing['id'], 'slug'=>slugify($listing['title'])])}}">
                                            <img class="card-item-image" src="{{ $listing['image'] }}">
                                        </a>
                                        <p class="card-light-text black-light capitalize">{{$listing['status']}}</p>                                       
                                    </div>
                                    <div class="reals-grid-details position-relative">
                                        <div class="location d-flex">
                                            <img src="{{asset('assets/frontend/images/icons/location-sky-blue2-20.svg')}}" alt="">                                           
                                            <p class="info"> {{ $listing['city'] .', '. $listing['country'] }} </p>
                                        </div>
                                        <div class="reals-grid-title mb-16">
                                            <a href="{{route('listing.details',['type'=>$type, 'id'=>$listing['id'], 'slug'=>slugify($listing['title'])])}}" class="title"> 
                                               
                                                {{$listing['title']}} </a>
                                            <p class="info"> {{substr_replace($listing['description'], "...", 50)}}</p>
                                        </div>
                                        <div class="reals-bed-bath-sqft d-flex align-items-center flex-wrap">
                                            <div class="item d-flex align-items-center">
                                                <img src="{{asset('assets/frontend/images/icons/bed-gray-16.svg')}}" alt="">
                                                <p class="total">{{$listing['bed'].' '.get_phrase('Bed')}}</p>
                                            </div>
                                            <div class="item d-flex align-items-center">
                                                <img src="{{asset('assets/frontend/images/icons/bath-gray-16.svg')}}" alt="">
                                                <p class="total">{{$listing['bath'].' '.get_phrase('Bath')}}</p>
                                            </div>
                                            <div class="item d-flex align-items-center">
                                                <img src="{{asset('assets/frontend/images/icons/resize-arrows-gray-16.svg')}}" alt="">
                                                <p class="total">{{$listing['size'].' '.get_phrase('sqft')}}</p>
                                            </div>
                                        </div>
                                        <div class="reals-grid-price-see d-flex align-items-center justify-content-between">
                                            <div class="prices d-flex">
                                                @if(!empty($listing['discount']))
                                                <p class="new-price">{{ currency($listing['discount']) }}</p>
                                                <p class="old-price">{{ currency($listing['price']) }}</p>
                                                @elseif(!empty($listing['price']))
                                                    <p class="new-price">{{ currency($listing['price']) }}</p>
                                                @endif
                                            </div>
                                            <a href="{{route('listing.details',['type'=>$type, 'id'=>$listing['id'], 'slug'=>slugify($listing['title'])])}}" class="reals-grid-view stretched-link">
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
        url: '{{ route("wishlist.update") }}', 
        method: 'POST', 
        data: {
            listing_id: listingId,
            type: 'work', 
            user_id: {{ auth()->check() ? auth()->id() : 'null' }}, 
            _token: '{{ csrf_token() }}',
        },
        success: function (response) {
            if (response.status === 'success') {
                success(response.message);
            } 
            else if (response.status === 'error') {
                bookmarkButton.toggleClass('active');
                const revertTooltipText = isActive ? 'Remove from Wishlist' : 'Add to Wishlist';
                bookmarkButton.attr('data-bs-title', revertTooltipText);
                const revertTooltipInstance = bootstrap.Tooltip.getInstance(button);
                if (revertTooltipInstance) revertTooltipInstance.dispose();
                new bootstrap.Tooltip(button);
            }
        },
        error: function (xhr) {
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