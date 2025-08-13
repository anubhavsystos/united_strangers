@extends('layouts.frontend')
@push('title', get_phrase('Home | United Strangers Directory Listing'))
@push('meta')@endpush
@push('css')
    <script src="{{ asset('assets/frontend/js/mixitup.min.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    @endpush
@section('frontend_layout')

        <style>
    .col-xl-3 {
        flex: 0 0 auto;
        width: 24%;
        margin-bottom: 25px;
    }

    .guest-selector {
        display: inline-flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 6px 12px;
        cursor: pointer;
        position: relative;
        width: fit-content;
        background: white;
    }

    .guest-selector span {
        margin: 0 6px;
        font-size: 16px;
    }

    .guest-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 8px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 8px;
        width: 220px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 12px;
        z-index: 1000;
    }

    .guest-dropdown .guest-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .guest-dropdown button {
        width: 28px;
        height: 28px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background: white;
        font-weight: bold;
        cursor: pointer;
    }

    .guest-dropdown button:hover {
        background-color: #f1f1f1;
    }

    .guest-dropdown .done-btn {
        width: 100%;
        padding: 6px;
        background: black;
        color: white;
        border: none;
        border-radius: 6px;
        margin-top: 8px;
    }

    .atn-search-filter-wrap .mh-filter-select {
    width: 80%;
}

.guest-selector {
    display: inline-flex
;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 6px 12px;
    cursor: pointer;
    position: relative;
    width: fit-content;
    background: white;
    margin-top: 40px;
}
</style>
    <div class="header-banner-wrap">
        <!-- Banner slider -->
        <div class="swiper banner-slider">
            <div class="swiper-wrapper">
                @php
                    $homeBanner = json_decode(get_frontend_settings('mother_homepage_banner'), true);
                @endphp
                @if (!empty($homeBanner) && is_array($homeBanner))
                    @foreach ($homeBanner as $banner)
                        <div class="swiper-slide">
                            <div class="banner-slider-wrap" style="background-image: url('{{ asset('uploads/mother_homepage_banner/' . $banner['image']) }}');">
                                <div class="banner-slider-content">
                                    <h1 class="mb-3 lg-title-78px text-white text-capitalize text-center">{{ $banner['title'] }}</h1>
                                    <p class="in-subtitle-16px text-white text-center max-w-621px mx-auto">{{ $banner['description'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fallback design when no banners are available --}}
                    <div class="swiper-slide">
                        <div class="banner-slider-wrap" style="background-image: url('{{ asset('assets/frontend/images/home/home-banner1.webp') }}');">
                            <div class="banner-slider-content">
                                <h1 class="mb-3 lg-title-78px text-white text-capitalize text-center"></h1>
                                <p class="in-subtitle-16px text-white text-center max-w-621px mx-auto"></p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <!-- Start Search Filter Area -->
    <section class="atn-search-filter-section mb-100px">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="atn-search-filter-wrap">
                        <ul class="nav nav-pills atn-search-nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation" onclick="show_products_segment('sleep')">
                                <button class="nav-link atn-search-nav-link active" id="sleep-tab" data-bs-toggle="pill" data-bs-target="#sleep" type="button" role="tab" aria-controls="sleep" aria-selected="true">
                                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.5622 17.0624H6.56218C3.09718 17.0624 2.45218 15.4499 2.28718 13.8824L1.72468 7.87495C1.64218 7.08745 1.61968 5.92495 2.39968 5.05495C3.07468 4.30495 4.19218 3.94495 5.81218 3.94495H13.3122C14.9397 3.94495 16.0572 4.31245 16.7247 5.05495C17.5047 5.92495 17.4822 7.08745 17.3997 7.88245L16.8372 13.8749C16.6722 15.4499 16.0272 17.0624 12.5622 17.0624ZM5.81218 5.06245C4.54468 5.06245 3.67468 5.30995 3.23218 5.80495C2.86468 6.20995 2.74468 6.83245 2.84218 7.76245L3.40468 13.7699C3.53218 14.9549 3.85468 15.9374 6.56218 15.9374H12.5622C15.2622 15.9374 15.5922 14.9549 15.7197 13.7624L16.2822 7.76995C16.3797 6.83245 16.2597 6.20995 15.8922 5.80495C15.4497 5.30995 14.5797 5.06245 13.3122 5.06245H5.81218Z" fill="#7E7E89" />
                                        <path d="M12.5625 5.0625C12.255 5.0625 12 4.8075 12 4.5V3.9C12 2.565 12 2.0625 10.1625 2.0625H8.9625C7.125 2.0625 7.125 2.565 7.125 3.9V4.5C7.125 4.8075 6.87 5.0625 6.5625 5.0625C6.255 5.0625 6 4.8075 6 4.5V3.9C6 2.58 6 0.9375 8.9625 0.9375H10.1625C13.125 0.9375 13.125 2.58 13.125 3.9V4.5C13.125 4.8075 12.87 5.0625 12.5625 5.0625Z" fill="#7E7E89" />
                                        <path d="M9.5625 12.5625C7.5 12.5625 7.5 11.2875 7.5 10.5225V9.75C7.5 8.6925 7.755 8.4375 8.8125 8.4375H10.3125C11.37 8.4375 11.625 8.6925 11.625 9.75V10.5C11.625 11.28 11.625 12.5625 9.5625 12.5625ZM8.625 9.5625C8.625 9.6225 8.625 9.69 8.625 9.75V10.5225C8.625 11.295 8.625 11.4375 9.5625 11.4375C10.5 11.4375 10.5 11.3175 10.5 10.515V9.75C10.5 9.69 10.5 9.6225 10.5 9.5625C10.44 9.5625 10.3725 9.5625 10.3125 9.5625H8.8125C8.7525 9.5625 8.685 9.5625 8.625 9.5625Z" fill="#7E7E89" />
                                        <path d="M11.0631 11.0776C10.7856 11.0776 10.5381 10.8676 10.5081 10.5826C10.4706 10.2751 10.6881 9.99006 10.9956 9.95256C12.9756 9.70506 14.8731 8.95506 16.4706 7.79256C16.7181 7.60506 17.0706 7.66506 17.2581 7.92006C17.4381 8.16756 17.3856 8.52006 17.1306 8.70756C15.3756 9.98256 13.3056 10.8001 11.1306 11.0776C11.1081 11.0776 11.0856 11.0776 11.0631 11.0776Z" fill="#7E7E89" />
                                        <path d="M8.06207 11.085C8.03957 11.085 8.01707 11.085 7.99457 11.085C5.93957 10.8525 3.93707 10.1025 2.20457 8.91755C1.94957 8.74505 1.88207 8.39255 2.05457 8.13755C2.22707 7.88255 2.57957 7.81505 2.83457 7.98755C4.41707 9.06755 6.23957 9.75005 8.11457 9.96755C8.42207 10.005 8.64707 10.2825 8.60957 10.59C8.58707 10.875 8.34707 11.085 8.06207 11.085Z" fill="#7E7E89" />
                                    </svg>
                                    <span>{{ get_phrase('Sleep') }}</span>
                                </button>
                            </li>
                            <input type="hidden" name="segment" id="segment" value="{{!empty(request()->get('segment')) ? request()->get('segment') : ''}}">
                            <li class="nav-item" role="presentation" onclick="show_products_segment('work')">
                                <button class="nav-link atn-search-nav-link" id="work-tab" data-bs-toggle="pill" data-bs-target="#work" type="button" role="tab" aria-controls="work" aria-selected="false">
                                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.3125 17.0625H4.3125C2.4975 17.0625 1.5 16.065 1.5 14.25V8.25C1.5 6.435 2.4975 5.4375 4.3125 5.4375H8.0625C8.37 5.4375 8.625 5.6925 8.625 6V14.25C8.625 15.435 9.1275 15.9375 10.3125 15.9375C10.62 15.9375 10.875 16.1925 10.875 16.5C10.875 16.8075 10.62 17.0625 10.3125 17.0625ZM4.3125 6.5625C3.1275 6.5625 2.625 7.065 2.625 8.25V14.25C2.625 15.435 3.1275 15.9375 4.3125 15.9375H7.91249C7.64249 15.495 7.5 14.9325 7.5 14.25V6.5625H4.3125Z" fill="#7E7E89" />
                                        <path d="M8.0625 6.5625H4.3125C4.005 6.5625 3.75 6.3075 3.75 6V4.5C3.75 3.36 4.6725 2.4375 5.8125 2.4375H8.14499C8.31749 2.4375 8.48251 2.51998 8.58751 2.65498C8.69251 2.79748 8.72999 2.9775 8.68499 3.1425C8.63999 3.3075 8.625 3.495 8.625 3.75V6C8.625 6.3075 8.37 6.5625 8.0625 6.5625ZM4.875 5.4375H7.5V3.75C7.5 3.6825 7.5 3.6225 7.5 3.5625H5.8125C5.295 3.5625 4.875 3.9825 4.875 4.5V5.4375Z" fill="#7E7E89" />
                                        <path d="M11.0625 10.3125C10.755 10.3125 10.5 10.0575 10.5 9.75V6C10.5 5.6925 10.755 5.4375 11.0625 5.4375C11.37 5.4375 11.625 5.6925 11.625 6V9.75C11.625 10.0575 11.37 10.3125 11.0625 10.3125Z" fill="#7E7E89" />
                                        <path d="M14.0625 10.3125C13.755 10.3125 13.5 10.0575 13.5 9.75V6C13.5 5.6925 13.755 5.4375 14.0625 5.4375C14.37 5.4375 14.625 5.6925 14.625 6V9.75C14.625 10.0575 14.37 10.3125 14.0625 10.3125Z" fill="#7E7E89" />
                                        <path d="M14.0625 17.0625H11.0625C10.755 17.0625 10.5 16.8075 10.5 16.5V13.5C10.5 12.78 11.0925 12.1875 11.8125 12.1875H13.3125C14.0325 12.1875 14.625 12.78 14.625 13.5V16.5C14.625 16.8075 14.37 17.0625 14.0625 17.0625ZM11.625 15.9375H13.5V13.5C13.5 13.395 13.4175 13.3125 13.3125 13.3125H11.8125C11.7075 13.3125 11.625 13.395 11.625 13.5V15.9375Z" fill="#7E7E89" />
                                        <path d="M5.0625 13.3125C4.755 13.3125 4.5 13.0575 4.5 12.75V9.75C4.5 9.4425 4.755 9.1875 5.0625 9.1875C5.37 9.1875 5.625 9.4425 5.625 9.75V12.75C5.625 13.0575 5.37 13.3125 5.0625 13.3125Z" fill="#7E7E89" />
                                        <path d="M14.8125 17.0625H10.3125C8.4975 17.0625 7.5 16.065 7.5 14.25V3.75C7.5 1.935 8.4975 0.9375 10.3125 0.9375H14.8125C16.6275 0.9375 17.625 1.935 17.625 3.75V14.25C17.625 16.065 16.6275 17.0625 14.8125 17.0625ZM10.3125 2.0625C9.1275 2.0625 8.625 2.565 8.625 3.75V14.25C8.625 15.435 9.1275 15.9375 10.3125 15.9375H14.8125C15.9975 15.9375 16.5 15.435 16.5 14.25V3.75C16.5 2.565 15.9975 2.0625 14.8125 2.0625H10.3125Z" fill="#7E7E89" />
                                    </svg>
                                    <span>{{ get_phrase('Work') }} </span>
                                </button>
                            </li>    
                            <li class="nav-item" role="presentation"  onclick="show_products_segment('play')">
                                <button class="nav-link atn-search-nav-link" id="play-tab" data-bs-toggle="pill" data-bs-target="#play" type="button" role="tab" aria-controls="play" aria-selected="false">
                                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.7891 17.0625H4.28906C1.47656 17.0625 1.47656 15.5025 1.47656 14.25V13.5C1.47656 12.78 2.06906 12.1875 2.78906 12.1875H16.2891C17.0091 12.1875 17.6016 12.78 17.6016 13.5V14.25C17.6016 15.5025 17.6016 17.0625 14.7891 17.0625ZM2.78906 13.3125C2.68406 13.3125 2.60156 13.395 2.60156 13.5V14.25C2.60156 15.48 2.60156 15.9375 4.28906 15.9375H14.7891C16.4766 15.9375 16.4766 15.48 16.4766 14.25V13.5C16.4766 13.395 16.3941 13.3125 16.2891 13.3125H2.78906Z" fill="#7E7E89" />
                                        <path d="M16.1031 13.3125H3.01562C2.70812 13.3125 2.45312 13.0575 2.45312 12.75V9.75C2.45312 6.675 4.63563 3.975 7.63563 3.3375C8.07812 3.24 8.54313 3.1875 9.01562 3.1875H10.1031C10.5831 3.1875 11.0481 3.24 11.4906 3.3375C14.4906 3.9825 16.6656 6.6825 16.6656 9.75V12.75C16.6656 13.0575 16.4181 13.3125 16.1031 13.3125ZM3.57812 12.1875H15.5406V9.75C15.5406 7.2075 13.7406 4.9725 11.2506 4.4325C10.8831 4.35 10.5006 4.3125 10.1031 4.3125H9.01562C8.62563 4.3125 8.24312 4.35 7.87563 4.4325C5.38563 4.965 3.57812 7.2 3.57812 9.75V12.1875Z" fill="#7E7E89" />
                                        <path d="M7.755 4.4475C7.5075 4.4475 7.2825 4.2825 7.215 4.035C7.155 3.8175 7.125 3.6 7.125 3.375C7.125 2.0325 8.22 0.9375 9.5625 0.9375C10.905 0.9375 12 2.0325 12 3.375C12 3.6 11.97 3.8175 11.91 4.035C11.835 4.32 11.55 4.5 11.25 4.4325C10.8825 4.35 10.5 4.3125 10.1025 4.3125H9.015C8.625 4.3125 8.2425 4.35 7.875 4.4325C7.8375 4.44 7.8 4.4475 7.755 4.4475ZM9.015 3.1875H10.1025C10.3575 3.1875 10.62 3.2025 10.8675 3.2325C10.8 2.5725 10.2375 2.0625 9.5625 2.0625C8.8875 2.0625 8.3325 2.5725 8.2575 3.2325C8.5125 3.2025 8.76 3.1875 9.015 3.1875Z" fill="#7E7E89" />
                                    </svg>
                                    <span>{{ get_phrase('Play') }}</span>
                                </button>
                            </li>                        
                        </ul>
                        <div class="tab-content atn-search-tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="sleep" role="tabpanel" aria-labelledby="sleep-tab" tabindex="0">
                                <form action="{{ route('ListingsFilter') }}" method="get">
                                    <input type="hidden" name="type" value="sleep">
                                    <input type="hidden" name="view" value="grid">
                                    <div class="atn-search-content">
                                        <div class="atn-single-search-item">
                                            <label class="atn-search-filter-label">{{ get_phrase('Country') }}</label>
                                            <select class="at-nice-select transparent2-nice-select mh-filter-select" name="country">
                                                <option value="all">{{ get_phrase('Select Country') }}</option>
                                                @if(!empty($sleepListing_data['sleepcountries']) && count($sleepListing_data['sleepcountries']) > 0)
                                                    @foreach($sleepListing_data['sleepcountries'] as $country)
                                                        <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="atn-single-search-item afNone">
                                            <label class="atn-search-filter-label">{{ get_phrase('City') }}</label>
                                            <select class="at-nice-select transparent2-nice-select mh-filter-select" name="city">
                                                <option value="all">{{ get_phrase('Select City') }}</option>
                                                @if(!empty($sleepListing_data['sleepcitys']) && count($sleepListing_data['sleepcitys']) > 0)
                                                    @foreach($sleepListing_data['sleepcitys'] as $city)
                                                        <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div> 
                                        <div class="atn-single-search-item">
                                            <label class=" atn-search-filter-label">{{ get_phrase('Check in') }}</label>
                                            <input type="date" class="form-control" name="" id="">
                                        </div>
                                        <div class="atn-single-search-item">
                                            <label class=" atn-search-filter-label">{{ get_phrase('Check out') }}</label>
                                            <input type="date" class="form-control"  name="" id="">
                                        </div>
                                        
                                        <div class="guest-selector" onclick="toggleGuestDropdown(event)">
    <i class="fas fa-user"></i>
    <span id="guestCount">1</span>
    <i class="fas fa-chevron-down"></i>

    <div class="guest-dropdown" id="guestDropdown">
        <div class="guest-row">
            <span>Adults</span>
            <div>
                <button onclick="updateGuest(event, 'adults', -1)">-</button>
                <span id="adultsCount" style="margin: 0 10px;">1</span>
                <button onclick="updateGuest(event, 'adults', 1)">+</button>
            </div>
        </div>

        <div class="guest-row">
            <span>Children</span>
            <div>
                <button onclick="updateGuest(event, 'children', -1)">-</button>
                <span id="childrenCount" style="margin: 0 10px;">0</span>
                <button onclick="updateGuest(event, 'children', 1)">+</button>
            </div>
        </div>
        <button class="done-btn" onclick="closeGuestDropdown()">Done</button>
    </div>
</div>

<script>
    const guestState = {
        adults: 1,
        children: 0,
        infants: 0
    };

    function toggleGuestDropdown(e) {
        e.stopPropagation();
        const dropdown = document.getElementById("guestDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    function updateGuest(e, type, change) {
        e.stopPropagation();
        const newVal = guestState[type] + change;
        if (type === 'adults' && newVal < 1) return;
        if (newVal < 0) return;
        guestState[type] = newVal;

        document.getElementById(`${type}Count`).innerText = newVal;

        const total = guestState.adults + guestState.children;
        const guestCountText = total + (guestState.infants > 0 ? ` + ${guestState.infants} infant` : '');
        document.getElementById('guestCount').innerText = guestCountText;
    }

    function closeGuestDropdown() {
        document.getElementById("guestDropdown").style.display = "none";
    }

    // Click outside to close
    document.addEventListener("click", function () {
        document.getElementById("guestDropdown").style.display = "none";
    });
</script>

                                      
                                        <button type="submit" class="btn at-btn-purple">
                                            <img src="{{ asset('assets/frontend/images/icons/search-white-20.svg') }}" alt="">
                                           <span>{{ get_phrase('Search') }}</span>
                                        </button>
                                    </div>
                                </form>
                                <div class="row mb-30px">
                                    <div class="col-12">
                                        <div class="d-flex gap-14px justify-content-center flex-wrap">
                                            @if(!empty($sleepListing_data['sleepcategorys']) && count($sleepListing_data['sleepcategorys']) > 0)
                                                @foreach($sleepListing_data['sleepcategorys'] as $index => $category)
                                                    <button type="button" data-filter=".work"  data-category_id="{{ $category['id'] }}" class="btn mh-filter-btn {{ $index === 0 ? 'mixitup-control-active' : '' }}">
                                                        {{ $category['name'] }}
                                                    </button>
                                                @endforeach
                                            @endif                                                                  
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="play" role="tabpanel" aria-labelledby="play-tab" tabindex="0">
                                <form action="{{ route('ListingsFilter') }}" method="get">
                                    <input type="hidden" name="type" value="play">
                                    <input type="hidden" name="view" value="grid">
                                    <div class="atn-search-content">                                      
                                        <div class="atn-single-search-item">
                                            <label class="atn-search-filter-label">{{ get_phrase('Country') }}</label>
                                            <select class="at-nice-select transparent2-nice-select mh-filter-select" name="country">
                                                <option value="all">{{ get_phrase('Select Country') }}</option>
                                                @if(!empty($playListing_data['playcountries']) && count($playListing_data['playcountries']) > 0)
                                                    @foreach($playListing_data['playcountries'] as $country)
                                                        <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="atn-single-search-item afNone">
                                            <label class="atn-search-filter-label">{{ get_phrase('City') }}</label>
                                            <select class="at-nice-select transparent2-nice-select mh-filter-select" name="city">
                                                <option value="all">{{ get_phrase('Select City') }}</option>
                                                @if(!empty($playListing_data['playcitys']) && count($playListing_data['playcitys']) > 0)
                                                    @foreach($playListing_data['playcitys'] as $city)
                                                        <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>                                       
                                        <button type="submit" class="btn at-btn-purple">
                                            <img src="{{ asset('assets/frontend/images/icons/search-white-20.svg') }}" alt="">
                                            <span>{{ get_phrase('Search') }}</span>
                                        </button>
                                    </div>
                                </form>
                                <div class="row mb-30px">
                                    <div class="col-12">
                                       <div class="d-flex gap-14px justify-content-center flex-wrap">
                                            @if(!empty($playListing_data['playcategorys']) && count($playListing_data['playcategorys']) > 0)
                                                @foreach($playListing_data['playcategorys'] as $index => $category)
                                                    <button type="button" data-filter=".play" data-category_id="{{ $category['id'] }}" class="btn mh-filter-btn {{ $index === 0 ? 'mixitup-control-active' : '' }}"> {{ $category['name'] }}
                                                    </button>
                                                @endforeach
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>                           
                            <div class="tab-pane fade" id="work" role="tabpanel" aria-labelledby="work-tab" tabindex="0">                          
                                <form action="{{ route('ListingsFilter') }}" method="get">
                                    <input type="hidden" name="type" value="work">
                                    <input type="hidden" name="view" value="grid">
                                    <input type="hidden" name="status" value="sell">
                                    <div class="d-flex align-items-center  real-search-filter-wrap">
                                       <div class="atn-single-search-item">
                                            <label class="atn-search-filter-label">{{ get_phrase('Country') }}</label>
                                            <select class="at-nice-select transparent2-nice-select mh-filter-select" name="country">
                                                <option value="all">{{ get_phrase('Select Country') }}</option>
                                                @if(!empty($workListing_data['workcountries']) && count($workListing_data['workcountries']) > 0)
                                                    @foreach($workListing_data['workcountries'] as $country)
                                                        <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="atn-single-search-item afNone">
                                            <label class="atn-search-filter-label">{{ get_phrase('City') }}</label>
                                            <select class="at-nice-select transparent2-nice-select mh-filter-select" name="city">
                                                <option value="all">{{ get_phrase('Select City') }}</option>
                                                @if(!empty($workListing_data['workcitys']) && count($workListing_data['workcitys']) > 0)
                                                    @foreach($workListing_data['workcitys'] as $city)
                                                        <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>  
                                        <div class="real-search-filter real-search-filter-border">
                                            <div class="d-flex align-items-center gap-2 mb-6px">
                                                <label class=" atn-search-filter-label">{{ get_phrase('Type') }}</label>
                                            </div>
                                            <select class="at-nice-select transparent2-nice-select mh-filter-select" name="status">
                                                <option value="all">{{ get_phrase('Choose Type') }}</option>
                                                <option value="rent">{{ get_phrase('Rent') }}</option>
                                                <option value="sell">{{ get_phrase('sell') }}</option>
                                            </select>
                                        </div>
                                        <div class="real-search-filter real-search-filter-border afNone">
                                            <div class="d-flex align-items-center gap-2 mb-6px">
                                                <label class="atn-search-filter-label">{{ get_phrase('Budget') }}</label>
                                            </div>
                                            
                                            <select id="searched_price" class="at-nice-select transparent2-nice-select mh-filter-select" onchange="updateHiddenFields(this)">
                                                <option value="all">{{ get_phrase('Choose your budget') }}</option>
                                                @if(!empty($workListing_data['workpricerange']) && count($workListing_data['workpricerange']) > 0)
                                                    @foreach($workListing_data['workpricerange'] as $range)
                                                        @php
                                                            $rangeParts = explode(' - ', $range['range']);
                                                            $min = $rangeParts[0];
                                                            $max = $rangeParts[1];
                                                        @endphp
                                                        <option value="{{ $min }}-{{ $max }}" data-min="{{ $min }}" data-max="{{ $max }}">
                                                            {{ $range['range'] }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <input type="hidden" id="min_price" name="min_price" value="">
                                            <input type="hidden" id="max_price" name="max_price" value="">
                                        </div>

                                        <button class="btn at-btn-purple">
                                            <img src="{{ asset('assets/frontend/images/icons/search-white-20.svg') }}" alt="">
                                            <span>{{ get_phrase('Search') }}</span>
                                        </button>
                                    </div>
                                </form>
                                <div class="row mb-30px">
                                    <div class="col-12">                                        
                                        <div class="d-flex gap-14px justify-content-center flex-wrap">
                                            @if(!empty($workListing_data['workcategorys']) && count($workListing_data['workcategorys']) > 0)
                                                @foreach($workListing_data['workcategorys'] as $index => $category)
                                                    <button type="button" data-filter=".work"  data-category_id="{{ $category['id'] }}" class="btn mh-filter-btn {{ $index === 0 ? 'mixitup-control-active' : '' }}">
                                                        {{ $category['name'] }}
                                                    </button>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Search Filter Area -->
   <section>
        <div class="container">
            <div class="row mb-30px">
                <div class="col-12">
                    <h1 class="in-title-36px text-center text-capitalize lh-1">{{ get_phrase('Featured Listings') }}</h1>
                </div>
            </div>
            <div class="mixitup2 row  mb-30px">
                 <span class="d-flex flex-wrap gap-0 justify-content-between mb-30px" id="show_detals_product"></span>
            </div>
            <span id="show_more_product"></span>
            <div id="view_more_button" class="row mb-5 mix hotel mt-5">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <a id="view_more_link" href="" class="btn at-btn-outline-dark">{{ get_phrase('View More') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <div class="scroll-icon-area">
        <a href="javascript:;" class="scroll-btn">
            <i class="fas fa-arrow-up"></i>
        </a>
    </div>
    <script src="{{ asset('assets/frontend/js/swiper-bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        document.addEventListener("DOMContentLoaded", function () {
            var segment = document.getElementById('segment').value;
            if (segment) {
                show_products_segment(segment);
            }
            var triggerEl = document.querySelector('[data-bs-target="#' + segment + '"]');
            if (triggerEl) {
                var tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }
        });

        function show_products_segment(segment) {
            var type = segment;
            $.ajax({
            url: "{{ route('show_products_segment') }}",
            type: 'GET',
            data: { type: type }, 
            success: function (response) {
                let html = '';
                const products = response.products || [];
                if(segment == 'sleep'){
                    products.forEach(item => {
                        html += `<div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mix ${response.type}">
                            <div class="single-grid-card">
                                <div class="grid-slider-area">
                                    <a class="w-100 h-100" href="${item.details_url}">
                                        <img class="card-item-image" src="${item.image_url}" alt="">
                                    </a>
                                    <p class="card-light-text black-light capitalize">${item.status}</p>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-title="${item.is_in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist'}"
                                        onclick="updateWishlist(this, '${item.id}')"
                                        class="grid-list-bookmark white-bookmark ${item.is_in_wishlist ? 'active' : ''}">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.4361 3C12.7326 3.01162 12.0445 3.22023 11.4411 3.60475C10.8378 3.98927 10.3407 4.53609 10 5.18999C9.65929 4.53609 9.16217 3.98927 8.55886 3.60475C7.95554 3.22023 7.26738 3.01162 6.56389 3C5.44243 3.05176 4.38583 3.57288 3.62494 4.44953C2.86404 5.32617 2.4607 6.48707 2.50302 7.67861C2.50302 10.6961 5.49307 13.9917 8.00081 16.2262C8.56072 16.726 9.26864 17 10 17C10.7314 17 11.4393 16.726 11.9992 16.2262C14.5069 13.9917 17.497 10.6961 17.497 7.67861C17.5393 6.48707 17.136 5.32617 16.3751 4.44953C15.6142 3.57288 14.5576 3.05176 13.4361 3Z" fill="#000000"/>
                                        </svg>
                                    </a>
                                </div>
                                <div class="reals-grid-details position-relative">
                                    <div class="location d-flex">
                                        <img src="/assets/frontend/images/icons/location-purple-16.svg" alt="">
                                        <p class="info">${item.city}, ${item.country}</p>
                                    </div>
                                    <div class="reals-grid-title mb-16">
                                        <a href="/listing/details/${response.type}/${item.id}/${item.slug}" class="title">
                                            ${item.is_verified ? '<span title="Verified">✔️</span>' : ''} ${item.title}
                                        </a>
                                        <p class="info">${item.description}</p>
                                    </div>
                                    <div class="reals-bed-bath-sqft d-flex align-items-center flex-wrap">
                                        <div class="item d-flex align-items-center">
                                            <img src="/assets/frontend/images/icons/bed-gray-16.svg" alt="">
                                            <p class="total">${item.bed} Bed</p>
                                        </div>
                                        <div class="item d-flex align-items-center">
                                            <img src="/assets/frontend/images/icons/bath-gray-16.svg" alt="">
                                            <p class="total">${item.bath} Bath</p>
                                        </div>
                                        <div class="item d-flex align-items-center">
                                            <img src="/assets/frontend/images/icons/resize-arrows-gray-16.svg" alt="">
                                            <p class="total">${item.size} sqft</p>
                                        </div>
                                    </div>
                                    <div class="reals-grid-price-see d-flex align-items-center justify-content-between">
                                        <div class="prices d-flex">
                                            ${item.discount
                                                ? `<p class="new-price">${item.discount}</p><p class="old-price">${item.price}</p>`
                                                : `<p class="new-price">${item.price}</p>`}
                                        </div>
                                        <a href="/listing/details/${response.type}/${item.id}/${item.slug}" class="reals-grid-view stretched-link">
                                            <img src="/image/12.svg" alt="">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                }
                if(segment == 'work'){
                    products.forEach(item => {
                            html += `<div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mix work">
                                    <div class="single-grid-card">
                                        <div class="grid-slider-area">
                                            <a class="w-100 h-100" href="${item.details_url}">
                                                <img class="card-item-image" src="${item.image}" alt="">
                                            </a>
                                            <p class="card-light-text black-light capitalize">${item.status}</p>
                                            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-title="${item.is_in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist'}"
                                            onclick="updateWishlist(this, '${item.id}')"
                                            class="grid-list-bookmark white-bookmark ${item.is_in_wishlist ? 'active' : ''}">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M13.4361 3C12.7326 3.01162 12.0445 3.22023 11.4411 3.60475C10.8378 3.98927 10.3407 4.53609 10 5.18999C9.65929 4.53609 9.16217 3.98927 8.55886 3.60475C7.95554 3.22023 7.26738 3.01162 6.56389 3C5.44243 3.05176 4.38583 3.57288 3.62494 4.44953C2.86404 5.32617 2.4607 6.48707 2.50302 7.67861C2.50302 10.6961 5.49307 13.9917 8.00081 16.2262C8.56072 16.726 9.26864 17 10 17C10.7314 17 11.4393 16.726 11.9992 16.2262C14.5069 13.9917 17.497 10.6961 17.497 7.67861C17.5393 6.48707 17.136 5.32617 16.3751 4.44953C15.6142 3.57288 14.5576 3.05176 13.4361 3Z" fill="#000000" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="reals-grid-details position-relative">
                                            <div class="location d-flex">
                                                <img src="/assets/frontend/images/icons/location-purple-16.svg" alt="">
                                                <p class="info">${item.city}, ${item.country}</p>
                                            </div>
                                            <div class="reals-grid-title mb-16">
                                                <a href="/listing/details/work/${item.id}/${item.slug}" class="title">
                                                    ${item.is_verified ? '<span title="Verified">✔️</span>' : ''} ${item.title}
                                                </a>
                                                <p class="info">${item.description}</p>
                                            </div>
                                            <div class="reals-bed-bath-sqft d-flex align-items-center flex-wrap">
                                                <div class="item d-flex align-items-center">
                                                    <img src="/assets/frontend/images/icons/bed-gray-16.svg" alt="">
                                                    <p class="total">${item.bed} Bed</p>
                                                </div>
                                                <div class="item d-flex align-items-center">
                                                    <img src="/assets/frontend/images/icons/bath-gray-16.svg" alt="">
                                                    <p class="total">${item.bath} Bath</p>
                                                </div>
                                                <div class="item d-flex align-items-center">
                                                    <img src="/assets/frontend/images/icons/resize-arrows-gray-16.svg" alt="">
                                                    <p class="total">${item.size} sqft</p>
                                                </div>
                                            </div>
                                            <div class="reals-grid-price-see d-flex align-items-center justify-content-between">
                                                <div class="prices d-flex">
                                                    ${item.discount
                                                        ? `<p class="new-price">${item.discount}</p><p class="old-price">${item.price}</p>`
                                                        : `<p class="new-price">${item.price}</p>`}
                                                </div>
                                                <a href="/listing/details/work/${item.id}/${item.slug}" class="reals-grid-view stretched-link">
                                                    <img src="/image/12.svg" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                        });
                }
                if(segment == 'play'){
                    response.products.forEach(function (item) {
                        html += `<div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mix play">
                            <div class="single-grid-card">
                                <div class="grid-slider-area">
                                    <a class="w-100 h-100" href="${item.details_url}">
                                        <img class="card-item-image" src="${item.image}" alt="play image">
                                    </a>
                                    ${item.is_popular ? `<p class="card-light-text theme-light capitalize">Popular</p>` : ''}
                                    <a href="javascript:void(0);" onclick="updateWishlistrest(this, '${item.id}')" class="grid-list-bookmark white-bookmark ${item.wishlist ? 'active' : ''}">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M13.4 3C..." fill="#000000"/>
                                        </svg>
                                    </a>
                                </div>
                                <a href="${item.details_url}" class="play-grid-link">
                                    <div class="restaurent-grid-details">
                                        <div class="restgrid-title-location">
                                            <h3 class="title">
                                                ${item.claimed ? `<span data-bs-toggle="tooltip" title="Verified">
                                                    <svg fill="none" height="18" viewBox="0 0 24 24" width="18"></svg></span>` : ''}
                                                ${item.title}
                                            </h3>
                                        </div>
                                        <div class="restgrid-price-rating d-flex align-items-center justify-content-between">
                                            <div class="location d-flex">
                                                <img src="/assets/frontend/images/icons/location-purple-16.svg" alt="">
                                                <p class="name f-14 ms-1">${item.city}, ${item.country}</p>
                                            </div>
                                            <div class="ratings d-flex align-items-center">
                                                <img src="/assets/frontend/images/icons/star-yellow-16.svg" alt="">
                                                <p class="rating">(${item.reviews_count})</p>
                                            </div>
                                        </div>
                                        <ul class="restgrid-list-items d-flex align-items-center flex-wrap">
                                            <li>Dine in</li>
                                            <li>Takeaway</li>
                                            <li>Delivery</li>
                                        </ul>
                                    </div>
                                </a>
                            </div>
                        </div>`;
                    });
                }
                $('#show_detals_product').html(html);
            
                if (response.show_view_more) {
                    const baseUrl = "{{ url('/') }}"; 
                    const domain = window.location.origin;
                    $('#view_more_link').attr('href', `${baseUrl}/listing/${response.type}/grid`);
                    $('#view_more_button').show();
                } else {
                    $('#view_more_button').hide();
                }
            },
            error: function () {
                $('#show_detals_product').html('<p>Error loading products.</p>');
            }
        });

        }

</script>



@endsection
