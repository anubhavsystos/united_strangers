@extends('layouts.admin')
@section('title', get_phrase('Update Listing'))
@push('css') 
@section('admin_layout')
    @include('admin.listing.listing_style')
    @php
        $tab = isset($tab) ? $tab : 0;
        $prefix = request()->route('prefix');
        $segment_type  = "play";
        $segment_id = $listing->id;
    @endphp
        
<div class="ol-card">
    <div class="ol-card-body p-3 d-flex align-items-center justify-content-between">
        <h3 class="title fs-16px d-flex align-items-center"> <i class="fi-rr-settings-sliders me-2"></i> {{ucwords($type).' '.get_phrase('Listing Update')}} on =>  {{$listing->title}}</h3>
        <a href="{{route('admin.listing.create')}}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
            <span class="fi-rr-plus"></span>
            <span> {{get_phrase('Add New Listing')}} </span>
        </a>
    </div>
</div>
    
    <div class="ol-card mt-3">
        <div class="ol-card-body p-3">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab == 0 ? 'active' : '' }}" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab" aria-controls="basic" aria-selected="true"> {{ get_phrase('Basic Info') }} </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab" aria-controls="address" aria-selected="false"> {{ get_phrase('Address') }} </button>
                </li>
                 <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab == 'feature' ? 'active' : '' }}" id="feature-tab" data-bs-toggle="tab" data-bs-target="#feature" type="button" role="tab" aria-controls="feature" aria-selected="false"> {{ get_phrase('Feature') }} </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab == 'menu' ? 'active' : '' }}" id="menu-tab" data-bs-toggle="tab" data-bs-target="#menu" type="button" role="tab" aria-controls="menu" aria-selected="false"> {{ get_phrase('Menu') }} </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments" type="button" role="tab" aria-controls="appointments" aria-selected="false"> {{ get_phrase('appointments') }} </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="calander-tab" data-bs-toggle="tab" data-bs-target="#calander" type="button" role="tab" aria-controls="calander" aria-selected="false"> {{ get_phrase('Calander') }} </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="open-time-tab" data-bs-toggle="tab" data-bs-target="#open-time" type="button" role="tab" aria-controls="open-time" aria-selected="false"> {{ get_phrase('Opening Time') }} </button>
                </li>   
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="Nearby-tab" data-bs-toggle="tab" data-bs-target="#Nearby" type="button" role="tab" aria-controls="Nearby" aria-selected="false"> {{ get_phrase('Nearby') }} </button>
                </li>             
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab" aria-controls="seo" aria-selected="false"> {{ get_phrase('Seo') }} </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab" aria-controls="media" aria-selected="false"> {{ get_phrase('Media') }} </button>
                </li>
               
            </ul>
            <form action="{{ route('admin.listing.update', ['type' => 'play', 'id' => $listing->id]) }}" id="form-action" method="post" enctype="multipart/form-data" class="position-relative">
                @csrf
                <div class="subMit eSubmit">
                    <button type="submit" id="form-action-btn" class="btn ol-btn-outline-secondary"> {{ get_phrase('Update') }} </button>
                </div>
                <input type="text" name="category" id="category" value="{{ $listing->category }}" class="d-none">
                <div class="tab-content pt-3" id="myTabContent">
                    <div class="tab-pane fade show {{ $tab == 0 ? 'active' : '' }}" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                        <div class="mb-3">
                            <label for="title" class="form-label ol-form-label"> {{ get_phrase('Listing title') }} *</label>
                            <input type="text" name="title" id="title" class="form-control ol-form-control" value="{{ $listing->title }}">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label ol-form-label"> {{ get_phrase('category') }} *</label>
                            <select name="category" id="category" class="form-control ol-form-control ol-select2" data-select2-id="select2-data-1-2ry6" tabindex="-1" aria-hidden="true">
                                <option value=""> {{ get_phrase('Select listing category') }} </option>
                                @foreach ($categories as $category)
                                    @if($category->type == 'work')
                                        <option value="{{$category->id}}" {{$category->id == $listing->category?'selected':''}}> {{$category->name}} </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label for="visibility" class="form-label ol-form-label"> {{ get_phrase('Visibility') }} *</label>
                                    <select name="visibility" id="visibility" class="form-control ol-form-control ol-select2" required data-minimum-results-for-search="Infinity">
                                        <option value=""> {{ get_phrase('Select listing visibility') }} </option>
                                        <option value="visible" {{ $listing->visibility == 'visible' ? 'selected' : '' }}> {{ get_phrase('Visible') }} </option>
                                        <option value="disable" {{ $listing->visibility == 'disable' ? 'selected' : '' }}> {{ get_phrase('Disable') }} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label for="is_popular" class="form-label ol-form-label"> {{ get_phrase('Type') }} *</label>
                                    <select name="is_popular" id="is_popular" class="form-control ol-form-control ol-select2" required data-minimum-results-for-search="Infinity">
                                        <option value=""> {{ get_phrase('Select Type') }} </option>
                                        <option value="featured" {{ $listing->is_popular == 'featured' ? 'selected' : '' }}> {{ get_phrase('Featured') }} </option>
                                        <option value="trending" {{ $listing->is_popular == 'trending' ? 'selected' : '' }}> {{ get_phrase('Trending') }} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="tax_persent" class="form-label ol-form-label">
                                        {{ get_phrase('Tax in Percent') }} *
                                    </label>
                                    <input type="number" name="tax_persent" id="tax_persent" class="form-control ol-form-control" value="{{$listing->tax_persent}}" placeholder="{{ get_phrase('Enter tax percent') }}" min="1" max="99" step="1"required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="wallet_price" class="form-label ol-form-label">
                                    {{ get_phrase('Wallet') }} 
                                </label>
                                <input type="number" name="wallet_price" id="wallet_price" class="form-control ol-form-control"  value="{{$listing->wallet_price}}" placeholder="{{ get_phrase('Enter Wallet Price') }}" >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="coins_price" class="form-label ol-form-label">
                                    {{ get_phrase('Coin') }} 
                                </label>
                                <input type="number" name="coins_price" id="coins_price" class="form-control ol-form-control" value="{{$listing->coins_price}}" placeholder="{{ get_phrase('Enter coin') }}" >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="rewards_price" class="form-label ol-form-label">
                                    {{ get_phrase('Rewards') }} 
                                </label>
                                <input type="number" name="rewards_price" id="rewards_price" class="form-control ol-form-control" value="{{$listing->rewards_price}}" placeholder="{{ get_phrase('Enter Rewards') }}" >
                            </div>
                        </div>
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label ol-form-label"> {{ get_phrase('Description') }} </label>
                                    <textarea name="description" id="description" cols="30" rows="3" placeholder="{{ get_phrase('Write your description') }}" class="form-control">{{ $listing->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="latitude" class="form-label ol-form-label"> {{ get_phrase('Latitude') }} *</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control ol-form-control" value="{{ $listing->Latitude }}" placeholder="{{ get_phrase('Enter Latitude code') }}">
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="longitude" class="form-label ol-form-label"> {{ get_phrase('Longitude') }} *</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control ol-form-control" value="{{ $listing->Longitude }}" placeholder="{{ get_phrase('Enter longitude code') }}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <link rel="stylesheet" href="{{ asset('assets/backend/css/leaflet.css') }}">
                                <script src="{{ asset('assets/backend/js/leaflet.js') }}"></script>
                                <div id="map" class="rounded h-400"></div>
                                <script type="text/javascript">
                                    "use strict";
                                    var map = L.map('map').setView([40.706486, -74.014700], 13);
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        maxZoom: 5,
                                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                    }).addTo(map);
                                    var popup = L.popup();
                                    function onMapClick(e) {
                                        var lat = e.latlng.lat.toFixed(5);
                                        var lng = e.latlng.lng.toFixed(5);
                                        popup
                                            .setLatLng(e.latlng)
                                            .setContent("You clicked at:<br>Latitude: " + lat + "<br>Longitude: " + lng)
                                            .openOn(map);
                                        document.getElementById('latitude').value = lat;
                                        document.getElementById('longitude').value = lng;
                                    }
                                    map.on('click', onMapClick);
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="open-time" role="tabpanel" aria-labelledby="open-time-tab">
                        <div class="row justify-content-center">
                            <div class="col-sm-5">
                                @php
                                    if (json_decode($listing->opening_time)) {
                                        $opening_times = json_decode($listing->opening_time);
                                    } else {
                                        $times = '{"saturday":{"open":"closed","close":"closed"},"sunday":{"open":"closed","close":"closed"},"monday":{"open":"closed","close":"closed"},"tuesday":{"open":"closed","close":"closed"},"wednesday":{"open":"closed","close":"closed"},"thursday":{"open":"closed","close":"closed"},"friday":{"open":"closed","close":"closed"}}';
                                        $opening_times = json_decode($times);
                                    }
                                @endphp
                                @foreach ($opening_times as $key => $day)
                                    <div class="mb-3">
                                        <label for="{{ $key }}_open" class="form-label ol-form-label"> {{ ucwords($key) . ' ' . get_phrase('Opening') }} </label>
                                        <select name="{{ $key }}_open" id="{{ $key }}}_open" class="form-control ol-form-control ol-select2" required data-minimum-results-for-search="Infinity">
                                            <option value="closed">{{ get_phrase('Closed') }}</option>
                                            <?php for($i = 0; $i < 24; $i++): ?>
                                            <option value="{{ $i }}" {{ $day->open == $i ? 'selected' : '' }}>{{ date('h:i A', strtotime("$i:00:00")) }}</option>
                                            <option value="{{ $i . ':30' }}" {{ $day->open == $i . ':30' ? 'selected' : '' }}> {{ date('h:i A', strtotime("$i:30:00")) }} </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-sm-5">
                                @foreach ($opening_times as $key => $day)
                                    <div class="mb-3">
                                        <label for="{{ $key }}_close" class="form-label ol-form-label"> {{ ucwords($key) . ' ' . get_phrase('closing') }} </label>
                                        <select name="{{ $key }}_close" id="{{ $key }}}_close" class="form-control ol-form-control ol-select2" required data-minimum-results-for-search="Infinity">
                                            <option value="closed">{{ get_phrase('Closed') }}</option>
                                            <?php for($i = 0; $i < 24; $i++): ?>
                                            <option value="{{ $i }}" {{ $day->close == $i ? 'selected' : '' }}>{{ date('h:i A', strtotime("$i:00:00")) }}</option>
                                            <option value="{{ $i . ':30' }}" {{ $day->close == $i . ':30' ? 'selected' : '' }}> {{ date('h:i A', strtotime("$i:30:00")) }} </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show {{ $tab == 'menu' ? 'active' : '' }}" id="menu" role="tabpanel" aria-labelledby="menu-tab">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h5 class="fs-16px title mb-3"> {{ get_phrase('Add Some Menu') }} </h5>
                            <a href="javascript:void(0)" onclick="modal('modal-md', '{{ route('admin.add.listing.menu', ['prefix' => 'admin', 'id' => $listing->id]) }}', '{{ get_phrase('Add New Menu') }}')" class="btn ol-btn-primary "> {{ get_phrase('Add Menu') }} </a>
                        </div>                        
                        <div class="row">
                            @foreach ($menus as $key => $menu)
                                <div class="col-sm-4">
                                    <input class="form-check-input d-none" name="menu[]" type="checkbox" value="{{ $menu->id }}" id="flexCheckDefault{{ $key }}" @if ($listing->menu && $listing->menu != 'null' && in_array($menu->id, json_decode($listing->menu))) checked @endif>
                                    <label class="form-check-label w-100" onclick="menu_select('{{ $key }}')" for="flexCheckDefault{{ $key }}">
                                        <div class="card mb-3 team-checkbox">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    <img class="h-88 object-fit" src="{{ get_all_image('menu/' . $menu->image) }}" height="50" width="50" class="img-fluid rounded-start" alt="...">
                                                </div>
                                                <div class="col-md-8 team-body">
                                                    <div class="card-body py-0 px-2 position-relative">
                                                        <p class="card-title line-1"> {{ $menu->title }} </p>
                                                        <p class="card-text line-1"> {{ $menu->sub_title }} </p>
                                                        <p class="card-text">
                                                            @if ($menu->dis_price)
                                                                <del> {{ currency($menu->price) }} </del>
                                                                <span> {{ currency($menu->dis_price) }} </span>
                                                            @else
                                                                <span> {{ currency($menu->price) }} </span>
                                                            @endif
                                                        </p>
                                                        <div class="text-end position-absulate">
                                                            <a data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('Edit') }}" href="javascript:void(0);" onclick="modal('modal-md', '{{ route('admin.edit.listing.menu', ['prefix' => 'admin', 'id' => $menu->id, 'listing_id' => $listing->id, 'page' => 'edit']) }}', '{{ get_phrase('Update Menu') }}')" class="p-1"> <i class="fas fa-edit"></i> </a>

                                                            <a data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('delete') }}" href="javascript:void(0);" onclick="delete_modal('{{ route('admin.delete.listing.menu', ['prefix' => 'admin', 'id' => $menu->id, 'listing_id' => $listing->id]) }}')" class="p-1"> <i class="fas fa-trash-alt"></i> </a>
                                                        </div>
                                                    </div>
                                                    <div class="checked @if ($listing->menu && $listing->menu != 'null' && in_array($menu->id, json_decode($listing->menu))) @else d-none @endif" id="menu-checked{{ $key }}">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade show {{($tab == 'feature')?'active':''}}" id="feature" role="tabpanel" aria-labelledby="feature-tab">
                        @include('admin.listing.feature_tab')
                    </div>
                    <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="engine_size" class="form-label ol-form-label"> {{ get_phrase('State') }} *</label>
                                    <select name="country" id="country" class="form-control ol-form-control ol-select2">
                                        <option value=""> {{ get_phrase('Select Listing State') }} </option>
                                        @foreach (App\Models\Country::get() as $country)
                                            <option value="{{ $country->id }}" {{ $listing->country == $country->id ? 'selected' : '' }}> {{ get_phrase($country->name) }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label ol-form-label"> {{ get_phrase('City') }} *</label>
                                    <select name="city" id="city" class="form-control ol-form-control ol-select2" data-minimum-results-for-search="Infinity">
                                        <option value="{{ $listing->city }}"> {{ App\Models\City::where('id', $listing->city)->first()->name }} </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label ol-form-label"> {{ get_phrase('Address') }} *</label>
                            <textarea name="address" id="list_address" cols="30" rows="3" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter listing address') }}">{{ $listing->address }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label for="post_code" class="form-label ol-form-label"> {{ get_phrase('Post Code') }} *</label>
                                    <input type="text" name="post_code" id="post_code" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter post code') }}" value="{{ $listing->postal_code }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="Nearby" role="tabpanel" aria-labelledby="Nearby-tab">
                        <div class="row mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap g-12 bd-b-1 pb-30">
                                <div class="tableTitle-3">
                                    <h4 class="fz-18-m-black">{{ get_phrase('Nearby Location') }}</h4>
                                </div>
                                <a href="javascript:;" onclick="modal('modal-lg', '{{ route('add-listing-nearBy', ['prefix' => 'admin', 'id' => $listing->id, 'type' => 'play']) }}', '{{ get_phrase('Add NearBy Location') }}')" class="btn ol-btn-primary ">{{ get_phrase('Add Nearby Location') }}</a>
                            </div>
                        </div>
                        <ul class="nav nav-tabs eNav-Tabs-custom nearby-tab" id="myTab" role="tablist">
                            <!-- School -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="cSchool-tab" data-bs-toggle="tab" data-bs-target="#cSchool" type="button" role="tab" aria-controls="cSchool" aria-selected="true">
                                    {{ get_phrase('School') }}
                                    <span></span>
                                </button>
                            </li>
                            <!-- Hospital -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cHospital-tab" data-bs-toggle="tab" data-bs-target="#cHospital" type="button" role="tab" aria-controls="cHospital" aria-selected="false">
                                    {{ get_phrase('Hospital') }}
                                    <span></span>
                                </button>
                            </li>
                            <!-- Shopping Center -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cShoppingCenter-tab" data-bs-toggle="tab" data-bs-target="#cShoppingCenter" type="button" role="tab" aria-controls="cShoppingCenter" aria-selected="false">
                                    {{ get_phrase('Shopping Center') }}
                                    <span></span>
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content eNav-Tabs-content" id="myTabContent">
                            <!-- School -->
                            <div class="tab-pane fade show active" id="cSchool" role="tabpanel" aria-labelledby="cSchool-tab">
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table eTable eTable-2 table-icon table-p0 mt-2">
                                        <tbody>
                                            @foreach ($nearbylocation as $nearby)
                                                @if ($nearby->nearby_id == 0)
                                                    <tr>
                                                        <td>
                                                            <div class="dl_property_type d-flex flex-column g-8">
                                                                <p class="form-label cap-form-label">
                                                                    {{ $nearby->name }}
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="nearBtn  justify-content-end d-flex gap-3">
                                                                <a data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('Edit') }}" href="javascript:void(0);" onclick="modal('modal-xl', '{{ route('editNearByLocation', ['prefix' => 'admin', 'id' => $nearby->id, 'page' => 'edit']) }}', '{{ get_phrase('Update') }}')" class="p-1"> <i class="fas fa-edit"></i> </a>
                                                                <a data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('delete') }}" href="javascript:void(0);" onclick="delete_modal('{{ route('deleteNearByLocation', ['prefix' => 'admin', 'id' => $nearby->id]) }}')" class="p-1"> <i class="fas fa-trash"></i> </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Hospital -->
                            <div class="tab-pane fade" id="cHospital" role="tabpanel" aria-labelledby="cHospital-tab">
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table eTable eTable-2 table-icon table-p0 mt-2">
                                        <tbody>
                                            @foreach ($nearbylocation as $nearby)
                                                @if ($nearby->nearby_id == 1)
                                                    <tr>
                                                        <td>
                                                            <div class="dl_property_type d-flex flex-column g-8">
                                                                <p class="form-label cap-form-label">
                                                                    {{ $nearby->name }}
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td>

                                                            <div class="nearBtn d-flex justify-content-end gap-3">
                                                                <a data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('Edit') }}" href="javascript:void(0);" onclick="modal('modal-xl', '{{ route('editNearByLocation', ['prefix' => 'admin', 'id' => $nearby->id, 'page' => 'edit']) }}', '{{ get_phrase('Update') }}')" class="p-1"> <i class="fas fa-edit"></i> </a>

                                                                <a data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('delete') }}" href="javascript:void(0);" onclick="delete_modal('{{ route('deleteNearByLocation', ['prefix' => 'admin', 'id' => $nearby->id]) }}')" class="p-1"> <i class="fas fa-trash"></i> </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Shopping Center -->
                            <div class="tab-pane fade" id="cShoppingCenter" role="tabpanel" aria-labelledby="cShoppingCenter-tab">
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table eTable eTable-2 table-icon table-p0 mt-2">
                                        <tbody>
                                            @foreach ($nearbylocation as $nearby)
                                                @if ($nearby->nearby_id == 2)
                                                    <tr>
                                                        <td>
                                                            <div class="dl_property_type d-flex flex-column g-8">
                                                                <p class="form-label cap-form-label">
                                                                    {{ $nearby->name }}
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="nearBtn d-flex justify-content-end gap-3 ">
                                                                <a data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('Edit') }}" href="javascript:void(0);" onclick="modal('modal-xl', '{{ route('editNearByLocation', ['prefix' => 'admin', 'id' => $nearby->id, 'page' => 'edit']) }}', '{{ get_phrase('Update') }}')" class="p-1"> <i class="fas fa-edit"></i> </a>
                                                                <a data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('delete') }}" href="javascript:void(0);" onclick="delete_modal('{{ route('deleteNearByLocation', ['prefix' => 'admin', 'id' => $nearby->id]) }}')" class="p-1"> <i class="fas fa-trash"></i> </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label ol-form-label"> {{ get_phrase('Meta Title') }}</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control ol-form-control" value="{{ $listing->meta_title }}" placeholder="{{ get_phrase('Enter meta title') }}">
                        </div>
                        <div class="mb-3">
                            <label for="keyword" class="form-label ol-form-label"> {{ get_phrase('Meta keywords') }}</label>
                            <input type="text" name="keyword" id="keyword" class="form-control ol-form-control" placeholder="{{ get_phrase('Keyword1; keyword2; keyword3;') }}" value="{{ $listing->meta_keyword }}">
                        </div>
                        <div class="mb-3">
                            <label for="meta_description" class="form-label ol-form-label"> {{ get_phrase('Meta Description') }} *</label>
                            <textarea name="meta_description" id="meta_description" cols="30" rows="3" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter meta description') }}">{{ $listing->meta_description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="og_title" class="form-label ol-form-label"> {{ get_phrase('OG title') }}</label>
                            <input type="text" name="og_title" id="og_title" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter og title') }}" value="{{ $listing->og_title }}">
                        </div>
                        <div class="mb-3">
                            <label for="canonical_url" class="form-label ol-form-label"> {{ get_phrase('Canonical URL') }}</label>
                            <input type="text" name="canonical_url" id="canonical_url" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter canonical URL') }}" value="{{ $listing->canonical_url }}">
                        </div>
                        <div class="mb-3">
                            <label for="og_description" class="form-label ol-form-label"> {{ get_phrase('OG Description') }} *</label>
                            <textarea name="og_description" id="og_description" cols="30" rows="3" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter meta description') }}">{{ $listing->og_description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="json_id" class="form-label ol-form-label"> {{ get_phrase('Json ID') }}</label>
                            <input type="text" name="json_id" id="json_id" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter json ID') }}" value="{{ $listing->json_id }}">
                        </div>
                        <div class="mb-3">
                            <label for="og_image" class="form-label ol-form-label"> {{ get_phrase('OG Image') }}</label>
                            <input type="file" name="og_image" id="og_image" class="form-control ol-form-control">
                        </div>

                    </div>
                    <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                        <div class="row">
                            <div class="col-sm-2">
                                <span> {{ get_phrase('Listing Images') }} :</span>
                            </div>
                            <div class="col-sm-10">
                                <div class="d-flex flex-wrap" id="image-container">
                                    <!-- Image previews will be dynamically added here -->
                                    @foreach (json_decode($listing->image) as $key => $image)
                                        <div class="possition_relative" id="image-icon{{ $key }}">
                                            <img class="object-fit" src="{{ get_all_image('listing-images/' . $image) }}" class="rounded" height="50" width="50">
                                            <a href="javascript:void(0);" onclick="listing_image_delete('{{ route('admin.listing.image.delete', ['type' => $type, 'id' => $listing->id, 'image' => $image]) }}', '{{ $key }}')"> <i data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('delete') }}" class="fas fa-trash-alt"></i> </a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label for="listing-icon-image" class="file-upload-label">
                                        <div class="label-bg">
                                            <span>{{ get_phrase('Click to upload SVG, PNG, JPG, or GIF') }} ({{ get_phrase('max 500 x 700px') }})</span>
                                        </div>
                                    </label>
                                    <input type="file" id="listing-icon-image" name="listing_image[]" class="form-control d-none" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                        <div class="row">                          
                            <div class="col-sm-12">                                        
                                <div class="ol-card mt-3">
                                    
                                    <div class="ol-card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <h5 class="fs-16px title mb-3"> {{get_phrase('Appointments Listing ')}} </h5>
                                        </div> 
                                        @if(count($appointments))
                                        <table id="datatable" class=" table nowrap w-100">
                                                <thead class="ca-thead">
                                                    <tr class="ca-tr">
                                                        <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Id')}}</th>                                                                
                                                        <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Customer')}}</th>
                                                        <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Contact')}}</th>
                                                        
                                                        <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Menu Name')}}</th>
                                                        <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Total Price')}}</th>
                                                        <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Person')}}</th>
                                                        <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Booking Date')}}</th>

                                                        <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Details')}}</th>                                                             
                                                        <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Status')}}</th>                                                                
                                                    </tr>
                                                </thead>
                                            <tbody>
                                                @foreach ($appointments as $key => $appointment)    
                                                    <tr class="ca-tr">
                                                        <td ><p class="ca-subtitle-14px ca-text-dark mb-2"> {{++$key}}.</p></td>  
                                                        <td class="min-w-140px">                                                                        
                                                            <p class="ca-subtitle-14px ca-text-dark mb-2 line-2"> {{isset($appointment['customer_name']) ? $appointment['customer_name'] : ''}}</p>
                                                            <p class="ca-subtitle-14px ca-text-dark mb-2 line-1"> {{!empty($appointment['name']) ? "For :" . $appointment['name'] : ''}}</p>
                                                        </td>
                                                        <td class="min-w-140px">
                                                            <div class="align-items-center gap-2"><p class="badge-dark">{{isset($appointment['customer_phone']) ? $appointment['customer_phone'] : ''}} </p>
                                                            <p class="ca-subtitle-14px ca-text-dark mb-2 line-1"> {{isset($appointment['phone']) ?  $appointment['phone'] : ''}}</p></div>
                                                        </td>
                                                        <td class="min-w-140px">
                                                            @php 
                                                                $menuSummary = is_array($appointment['menu_summary']) 
                                                                    ? $appointment['menu_summary'] 
                                                                    : explode(',', $appointment['menu_summary']);
                                                            @endphp

                                                            <div class="d-flex flex-column">
                                                                @foreach(array_slice($menuSummary, 0, 2) as $item)
                                                                    <p class="badge-dark mb-1">{{ trim($item) }}</p>
                                                                @endforeach

                                                                @if(count($menuSummary) > 2)
                                                                    <a href="javascript:void(0);" 
                                                                    onclick="this.nextElementSibling.classList.toggle('d-none'); this.classList.add('d-none')">
                                                                    ... Show more
                                                                    </a>

                                                                    <div class="d-none">
                                                                        @foreach(array_slice($menuSummary, 2) as $item)
                                                                            <p class="badge-dark mb-1">{{ trim($item) }}</p>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <td class="min-w-140px">
                                                            <div class="d-flex align-items-center gap-2"><p class="badge-dark">{{isset($appointment['total_price']) ? $appointment['total_price'] : ''}} </p></div>
                                                        </td>
                                                        <td class="min-w-140px">
                                                            <div class="d-flex gap-1 align-items-center">
                                                                <p class="in-subtitle-14px">Adults :{{!empty($appointment['adults']) ? $appointment['adults'] : ''}} Child : {{!empty($appointment['child']) ? $appointment['child'] : ''}}</p>
                                                            </div> 
                                                        </td>
                                                        <td class="min-w-140px">
                                                            <div class="d-flex align-items-center gap-2"><p class="badge-dark">{{isset($appointment['date']) ? $appointment['date'] : ''}} </p></div>
                                                        </td>
                                                        <td class="min-w-110px">
                                                            <div class="d-flex gap-1 align-items-center">
                                                                <img src="{{ asset('assets/frontend/images/icons/clock-gray-12.svg') }}" alt="icon">
                                                                <p class="in-subtitle-14px">{{!empty($appointment['in_time']) ? $appointment['in_time'] : ''}} - {{!empty($appointment['out_time']) ? $appointment['out_time'] : ''}}</p>
                                                            </div>                                                                    
                                                            <div class="eMessage">
                                                                <p class="ca-subtitle-14px ca-text-dark mb-6px mb-2">
                                                                    <span class="short-text d-inline">
                                                                        {{ \Illuminate\Support\Str::words($appointment['message'], 30, '...') }}
                                                                    </span>
                                                                    <span class="full-text d-none">
                                                                        {{ $appointment['message'] }}
                                                                    </span>                                                                                
                                                                </p>
                                                                @if(str_word_count($appointment['message']) > 30)
                                                                    <a href="javascript:void(0)" class="read-more">{{ get_phrase('Read More') }}</a>
                                                                @endif
                                                            </div>                                                                        
                                                        </td>                                                                     

                                                        <td>
                                                            @if ($appointment['status'] == 1)
                                                                <p class="badge-success-light">{{get_phrase('Successfully Ended')}}</p>
                                                            @else
                                                                <p class="badge-danger-light">{{get_phrase('Not start yet')}}</p>
                                                            @endif
                                                        </td>
                                        
                                                    </tr>
                                                    @endforeach
                                            </tbody>
                                        </table>
                                        @else
                                            @include('layouts.no_data_found')
                                        @endif
                                    </div>
                                </div>                                   
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="calander" role="tabpanel" aria-labelledby="calander-tab">
                        <div class="row">  
                            <div class="col-sm-12">
                                <div class="container mt-4">
                                    <h2>Appointment Calendar</h2>
                                    <div id="calendar"></div>
                                </div>                               
                            </div>
                        </div>
                    </div>                    
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="appointmentModalPlay" tabindex="-1">
        <div class="modal-dialog">
            <form id="appointmentFormPlay" method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <input type="hidden" name="listing_type" value="play">
                <input type="hidden" name="listing_id" value="{{isset($listing->id) ? $listing->id : ''}}">

                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Play Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer Contact</label>
                        <input type="number" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Appointment Date</label>
                        <input type="date" class=" appointmentDate form-control mform-control flat-input-picker3 input-calendar-icon"  id="appointmentDate" name="date" >
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label class="form-label">Adults</label>
                            <input type="number" class="form-control" name="adults" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Child</label>
                            <input type="number" class="form-control" name="child" required>
                        </div>
                    </div>
                   <div class="mb-3">
                        <label class="form-label">Select Menus</label>
                        <div class="row">
                            @foreach($menus as $menu)
                                <div class="col-md-6 mb-2">
                                    <div class="card p-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <input type="checkbox" class="form-check-input menu-checkbox me-2" name="menu_id[]" value="{{ $menu->id }}" data-title="{{ $menu->title }}"data-price="{{ $menu->price }}" onclick="updateTotal()">
                                            <span>{{ $menu->title }} ({{ currency($menu->price) }})</span>
                                            <input type="number" class="form-control form-control-sm ms-2 menu-qty" name="menu_qty[{{ $menu->id }}]" value="1" min="1" style="width:80px;" oninput="updateTotal()">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="total_price" id="total_price">
                        <input type="hidden" name="menu_summary" id="menu_summary">
                        <div class="mt-2">
                            <strong>Total: </strong> <span id="total_display">0</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="message" id="message" cols="30" rows="3" placeholder="{{ get_phrase('Write your description') }}" class="form-control"></textarea>                        
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Play</button>
                </div>
                </div>
            </form>
        </div>
    </div>
    @include('admin.listing.listing_script')
    <script>
        "use strict";
        $("#form-action-btn").on('click', function() {
            event.preventDefault();
            var listing_category = $("#category").val();
            if (!listing_category) {
                warning('Listing category is required');
            }
            var listing_title = $("#title").val();
            if (!listing_title) {
                warning('Listing title is required');
            }
            var listing_country = $("#country").val();
            if (!listing_country) {
                warning('Listing State is required');
            }
            var listing_city = $("#city").val();
            if (!listing_city) {
                warning('Listing city is required');
            }
            var listing_address = $("#list_address").val();
            if (!listing_address) {
                warning('Listing address is required');
            }
            var listing_post_code = $("#post_code").val();
            if (!listing_post_code) {
                warning('Listing post code is required');
            }
            var listing_latitude = $("#latitude").val();
            if (!listing_latitude) {
                warning('Listing latitude is required');
            }
            var listing_longitude = $("#longitude").val();
            if (!listing_longitude) {
                warning('Listing longitude is required');
            }
            var listing_visibility = $("#visibility").val();
            if (!listing_visibility) {
                warning('Listing visibility is required');
            }
            if (listing_title && listing_category && listing_country && listing_city && listing_address && listing_post_code && listing_latitude && listing_longitude && listing_visibility) {
                $("#form-action").trigger('submit');
            }

        })
    </script>

    <script>
        "use strict";
        $(document).ready(function() {
            $("#country").on('change', function() {
                var country = $("#country").val();
                var url = "{{ route('admin.country.city', ['id' => ':id']) }}";
                url = url.replace(':id', country);
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(result) {
                        var cityDropdown = $("#city");
                        cityDropdown.html('<option value="">{{ get_phrase('City') }}</option>');
                        $.each(result, function(index, city) {
                            cityDropdown.append('<option value="' + city.id + '">' + city.name + '</option>');
                        });
                    },

                });
            });
        });
    </script>

    <script>
        document.getElementById('listing-icon-image').addEventListener('change', function(event) {
        const imageContainer = document.getElementById('image-container');
        const files = event.target.files;

        for (const file of files) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imageIcon = document.createElement('div');
                imageIcon.classList.add('image-icon');
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Selected image';
                
                const trashIcon = document.createElement('i');
                trashIcon.classList.add('fas', 'fa-trash-alt');
                trashIcon.addEventListener('click', function() {
                    imageIcon.remove();
                });

                imageIcon.appendChild(img);
                imageIcon.appendChild(trashIcon);
                imageContainer.appendChild(imageIcon);
            }
            
            reader.readAsDataURL(file);
        }
    }); 
    </script>    
<script>
function updateTotal() {
    let total = 0;
    let summary = [];

    document.querySelectorAll('.menu-checkbox').forEach((checkbox) => {
        if (checkbox.checked) {
            let price = parseFloat(checkbox.getAttribute('data-price')) || 0;
            let title = checkbox.getAttribute('data-title') || '';
            let qtyInput = checkbox.closest('.d-flex').querySelector('.menu-qty');
            let qty = parseInt(qtyInput.value) || 1;

            total += price * qty;

            summary.push( qty + ' ' + title);
        }
    });

    document.getElementById('total_display').innerText = total;
    document.getElementById('total_price').value = total;

    // Save summary as comma-separated string
    document.getElementById('menu_summary').value = summary.join(', ');
}
</script>


@endsection
