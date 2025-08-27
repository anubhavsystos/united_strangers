@extends('layouts.admin')
@if(isset($offer))
    @section('title', get_phrase('Update Offer'))
@else
    @section('title', get_phrase('Create Offer'))
@endif

@section('admin_layout')
@include('admin.listing.listing_style')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-20px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                @if(isset($offer))
                    {{ get_phrase('Update offer') }}
                @else
                    {{ get_phrase('Create offer') }}
                @endif
                
            </h4>
        </div>
    </div>
</div>
    @if(isset($offer))
        <form action="{{ route('admin.offer.update', ['id' => $offer->id]) }}" method="POST" enctype="multipart/form-data">
    @else
        <form action="{{route('admin.offer.store')}}" id="form-action" method="post" enctype="multipart/form-data" class="position-relative">
    @endif
    @csrf
    <div class="ol-card mt-3">
        <div class="ol-card-body p-3">
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="type" class="form-label ol-form-label"> {{get_phrase('Offer Segment')}} </label>
                        <select name="segment_type" id="offer-type"  class="form-control ol-select2 ol-form-control" data-minimum-results-for-search="Infinity" >                        
                        <option value="">{{ get_phrase('Select Offer type') }}</option>
                        <option value="work" {{ (isset($offer) && $offer->segment_type == 'work') ? 'selected' : '' }}>
                            {{ get_phrase('Work Offer') }}
                        </option>
                        <option value="sleep" {{ (isset($offer) && $offer->segment_type == 'sleep') ? 'selected' : '' }}>
                            {{ get_phrase('Sleep Offer') }}
                        </option>
                        <option value="play" {{ (isset($offer) && $offer->segment_type == 'play') ? 'selected' : '' }}>
                            {{ get_phrase('Play Offer') }}
                        </option>
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
                                    @if(!empty($segmentName))                                 
                                        <option value="" selected> {{isset($segmentName) ? $segmentName : ''}} </option>     
                                    @endif                              
                                </select>
                            </div>
                        </div>
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
            <div class="subMit {{ isset($offer) ? 'eSubmit1' : 'eSubmit2' }}">
                <button type="submit" id="form-action-btn" class="btn ol-btn-outline-secondary">  {{ isset($offer) ? get_phrase('Update') : get_phrase('Create') }}</button>
            </div>
            <div class="tab-content pt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                    <div class="mb-3">
                        <label for="title" class="form-label ol-form-label"> {{get_phrase('Offer Title')}} *</label>
                        <input type="text" name="title" id="title" value="{{ isset($offer) ? $offer->title : old('title') }}" required class="form-control ol-form-control" placeholder="{{get_phrase('Enter offer title')}}" >
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="title" class="form-label ol-form-label"> {{get_phrase('To Date')}} *</label>
                                <input type="date" name="to_date" required id="title" class="form-control ol-form-control" value="{{ isset($offer) ? $offer->to_date : old('to_date') }}" placeholder="{{get_phrase('To Date')}}" >
                            </div>
                            <div class="col-sm-6">
                                <label for="title" class="form-label ol-form-label"> {{get_phrase('From Date')}} *</label>
                                <input type="date" name="from_date"  required id="title" class="form-control ol-form-control" value="{{ isset($offer) ? $offer->from_date : old('from_date') }}" placeholder="{{get_phrase('From Date')}}" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="visibility" class="form-label ol-form-label"> {{get_phrase('Visibility')}} *</label>
                                <select name="visibility" id="visibility" class="form-control ol-form-control">
                                    <option value="">{{ get_phrase('Select offer visibility') }}</option>
                                    <option value="visible" {{ old('visibility', $offer->visibility ?? '') == 'visible' ? 'selected' : '' }}>
                                        {{ get_phrase('Visible') }}
                                    </option>
                                    <option value="disable" {{ old('visibility', $offer->visibility ?? '') == 'disable' ? 'selected' : '' }}>
                                        {{ get_phrase('Disable') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="description" class="form-label ol-form-label"> {{get_phrase('Description')}} </label>
                                <textarea name="description" id="description" cols="30" rows="3" placeholder="{{get_phrase('Write your description')}}" class="form-control">{{ isset($offer) ? $offer->description : old('description') }}</textarea>
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
                        <label for="meta_title" class="form-label ol-form-label"> {{ get_phrase('Meta Title') }}</label>
                        <input type="text" name="meta_title" id="meta_title" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter meta title') }}" value="{{ old('meta_title', $offer->meta_title ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="keyword" class="form-label ol-form-label"> {{ get_phrase('Meta keywords') }}</label>
                        <input type="text" name="keyword" id="keyword" class="form-control ol-form-control" placeholder="{{ get_phrase('Keyword1; keyword2; keyword3;') }}" value="{{ old('keyword', $offer->keyword ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label ol-form-label"> {{ get_phrase('Meta Description') }} </label>
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter meta description') }}">{{ old('meta_description', $offer->meta_description ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="og_title" class="form-label ol-form-label"> {{ get_phrase('OG title') }}</label>
                        <input type="text" name="og_title" id="og_title" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter og title') }}" value="{{ old('og_title', $offer->og_title ?? '') }}">
                    </div>                

                    <div class="mb-3">
                        <label for="og_description" class="form-label ol-form-label"> {{ get_phrase('OG Description') }} </label>
                        <textarea name="og_description" id="og_description" cols="30" rows="3" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter og description') }}">{{ old('og_description', $offer->og_description ?? '') }}</textarea>
                    </div>          
                </div>
                <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                    <div class="row">
                        <div class="col-sm-2">
                            <span> {{get_phrase('Listing Images')}} :</span>
                        </div>
                        <div class="col-sm-10">
                                <div class="d-flex flex-wrap" id="image-container">
                                    <!-- Image previews will be dynamically added here -->
                                    @if(!empty($offer->image))
                                        @foreach (json_decode($offer->image) as $key => $image)
                                            <div class="possition_relative" id="image-icon{{ $key }}">
                                                <img class="object-fit" src="{{ get_all_image('offer-images/' . $image) }}" class="rounded" height="50" width="50">
                                                <a href="javascript:void(0);" onclick="listing_image_delete('{{ route('admin.offers.image.delete', ['id' => $offer->id, 'image' => $image]) }}', '{{ $key }}')"> <i data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('delete') }}" class="fas fa-trash-alt"></i> </a>
                                            </div>
                                        @endforeach
                                    @endif
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
            </div>
        </div>
    </div>
</form>
@include('admin.listing.listing_script')

    </div>
</div>

@endsection
@push('js')


@endpush