@extends('layouts.admin')
@section('title', get_phrase('Create offer'))
@section('admin_layout')
@include('admin.listing.listing_style')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-20px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Create offer') }}
            </h4>
        </div>
    </div>
</div>
<form action="{{route('admin.offer.store')}}" id="form-action" method="post" enctype="multipart/form-data" class="position-relative">
    @csrf
    <div class="ol-card mt-3">
        <div class="ol-card-body p-3">
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="type" class="form-label ol-form-label"> {{get_phrase('Offer Segment')}} </label>
                        <select name="segment_type" id="offer-type" class="form-control ol-select2 ol-form-control" data-minimum-results-for-search="Infinity">
                            <option value=""> {{get_phrase('Select Offer type')}} </option>
                            <option value="work"> {{get_phrase('Work Offer')}} </option>
                            <option value="sleep"> {{get_phrase('Sleep Offer')}} </option>
                            <option value="play"> {{get_phrase('Play Offer')}} </option>
                        </select>
                    </div>
                </div> 
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="offer-category" class="form-label ol-form-label"> {{get_phrase('Offer on')}} </label>
                         <div class="col-sm-12">
                            <div class="mb-3">                               
                                <select name="segment_id" id="offer-category" class="form-control ol-form-control ol-select22 ol-select2" >
                                    <option value=""> {{get_phrase('Select Offer Segment')}} </option>                                   
                                </select>
                            </div>
                        </div>
                        <!-- <select name="segment_id" id="offer-category" class="form-control form-control ol-form-control ol-select22 ol-select2 " data-minimum-results-for-search="Infinity">
                            <option value=""> {{get_phrase('Select Offer Segment')}} </option>
                        </select> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ol-card mt-3">
        <div class="ol-card-body p-3" id="offer-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab" aria-controls="basic" aria-selected="true"> {{get_phrase('Basic Info')}} </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab" aria-controls="seo" aria-selected="false"> {{get_phrase('Seo')}} </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab" aria-controls="media" aria-selected="false"> {{get_phrase('Media')}} </button>
                </li>
            </ul>
            <div class="subMit eSubmit2">
                <button type="submit" id="form-action-btn" class="btn ol-btn-outline-secondary"> {{get_phrase('Create')}} </button>
            </div>
            <div class="tab-content pt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                    <div class="mb-3">
                        <label for="title" class="form-label ol-form-label"> {{get_phrase('Offer Title')}} *</label>
                        <input type="text" name="title" id="title" required class="form-control ol-form-control" placeholder="{{get_phrase('Enter offer title')}}" >
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="title" class="form-label ol-form-label"> {{get_phrase('To Date')}} *</label>
                                <input type="date" name="to_date" required id="title" class="form-control ol-form-control" placeholder="{{get_phrase('Enter offer title')}}" >
                            </div>
                            <div class="col-sm-6">
                                <label for="title" class="form-label ol-form-label"> {{get_phrase('From Date')}} *</label>
                                <input type="date" name="from_date"  required id="title" class="form-control ol-form-control" placeholder="{{get_phrase('Enter offer title')}}" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="visibility" class="form-label ol-form-label"> {{get_phrase('Visibility')}} *</label>
                                <select name="visibility" id="visibility" class="form-control ol-form-control " >
                                    <option value=""> {{get_phrase('Select offer visibility')}} </option>
                                    <option value="visible"> {{get_phrase('Visible')}} </option>
                                    <option value="disable"> {{get_phrase('Disable')}} </option>
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="description" class="form-label ol-form-label"> {{get_phrase('Description')}} </label>
                                <textarea name="description" id="description" cols="30" rows="3" placeholder="{{get_phrase('Write your description')}}" class="form-control"></textarea>
                            </div>
                        </div>
                        
                        <div class="row">             
                            <div class="col-sm-12">
                                <link rel="stylesheet" href="{{asset('assets/backend/css/leaflet.css')}}">
                                <script src="{{asset('assets/backend/js/leaflet.js')}}"></script>
                                <div id="map"></div>
                            </div>
                        </div>
                    </div>          
                </div>
            
                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label ol-form-label"> {{get_phrase('Meta Title')}}</label>
                        <input type="text" name="meta_title" id="meta_title" class="form-control ol-form-control" placeholder="{{get_phrase('Enter meta title')}}" >
                    </div>
                    <div class="mb-3">
                        <label for="keyword" class="form-label ol-form-label"> {{get_phrase('Meta keywords')}}</label>
                        <input type="text" name="keyword" id="keyword" class="form-control ol-form-control" placeholder="{{get_phrase('Keyword1; keyword2; keyword3;')}}" >
                    </div>
                    <div class="mb-3">
                        <label for="meta_description" class="form-label ol-form-label"> {{get_phrase('Meta Description')}} </label>
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3" class="form-control ol-form-control" placeholder="{{get_phrase('Enter meta description')}}" ></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="og_title" class="form-label ol-form-label"> {{get_phrase('OG title')}}</label>
                        <input type="text" name="og_title" id="og_title" class="form-control ol-form-control" placeholder="{{get_phrase('Enter og title')}}" >
                    </div>
                
                    <div class="mb-3">
                        <label for="og_description" class="form-label ol-form-label"> {{get_phrase('OG Description')}} </label>
                        <textarea name="og_description" id="og_description" cols="30" rows="3" class="form-control ol-form-control" placeholder="{{get_phrase('Enter meta description')}}" ></textarea>
                    </div>          
                </div>
                <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                    <div class="row">
                        <div class="col-sm-2">
                            <span> {{get_phrase('Listing Images')}} :</span>
                        </div>
                        <div class="col-sm-10">
                            <div class="d-flex flex-wrap" id="image-container"> </div>
                            <div class="form-group">
                                <label for="listing-icon-image" class="file-upload-label">
                                    <div class="label-bg">
                                        <span>{{get_phrase('Click to upload SVG, PNG, JPG, or GIF')}} ({{get_phrase('max 500 x 700px')}})</span>
                                    </div>
                                </label>
                                <input type="file" id="listing-icon-image" name="listing_image[]" class="form-control d-none" multiple>
                            </div>  
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>
</form>
@include('admin.listing.listing_script')
<script>
    "use strict";
    // $("#form-action-btn").on('click', function() {
    //     event.preventDefault(); 
    //     var offer_category = $("#category").val();
    //     if(!offer_category){
    //         warning('offer category is  ');
    //     }
    //     var offer_title = $("#title").val();
    //     if(!offer_title){
    //         warning('offer title is  ');
    //     }
    //     var offer_offer_icon_image = $("#offer-icon-image").val();
    //     if(!offer_offer_icon_image){
    //         warning('offer image is  ');
    //     }
    //     var offer_country = $("#country").val();
    //     if(!offer_country){
    //         warning('offer country is  ');
    //     }
    //     var offer_city = $("#city").val();
    //     if(!offer_city){
    //         warning('offer city is  ');
    //     }
    //     var offer_address = $("#list_address").val();
    //     if(!offer_address){
    //         warning('offer address is  ');
    //     }
    //     var offer_post_code = $("#post_code").val();
    //     if(!offer_post_code){
    //         warning('offer post code is  ');
    //     }
    //     var offer_latitude = $("#latitude").val();
    //     if(!offer_latitude){
    //         warning('offer latitude is  ');
    //     }
    //     var offer_longitude = $("#longitude").val();
    //     if(!offer_longitude){
    //         warning('offer longitude is  ');
    //     }
    //     var offer_visibility = $("#visibility").val();
    //     if(!offer_visibility){
    //         warning('offer visibility is  ');
    //     }
    //     if(offer_offer_icon_image && offer_title && offer_category && offer_country && offer_city && offer_address && offer_post_code && offer_latitude && offer_longitude && offer_visibility){
    //         $("#form-action").trigger('submit');
    //     }
    // })
</script>
    </div>
</div>

@endsection
@push('js')


@endpush