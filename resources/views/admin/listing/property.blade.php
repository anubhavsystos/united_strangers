@php 
    $user_prefix = (user('role') == 1) ? 'admin' : 'agent'; 
@endphp

@if ($action == 'add')
<form action="{{ route('admin.store.listing.property',['prefix'=>$user_prefix, 'id'=>$id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="title" class="form-label ol-form-label">{{ get_phrase('Property Title') }}</label>
                <input type="text" name="title" id="title" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter Property title') }}" required>
                <input type="hidden" name="segment_type" id="segment_type" value="work">
            </div>
            <div class="mb-3">
                <label for="person" class="form-label ol-form-label">{{ get_phrase('Number of persons') }}</label>
                <input type="number" name="person" id="person" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter number of person') }}" required>
            </div>
            <div class="mb-3">
                <label for="child" class="form-label ol-form-label">{{ get_phrase('Number of Child') }}</label>
                <input type="number" name="child" id="child" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter number of child') }}">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label ol-form-label">{{ get_phrase('Property Price') }}</label>
                <input type="number" name="price" id="price" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter Property price') }}" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label ol-form-label">{{ get_phrase('Property Images') }}</label>
                <input type="file" name="image[]" id="image" class="form-control ol-form-control" multiple required>
            </div>
        </div>
       <div class="col-sm-6">
    {{-- Features --}}
            <div class="mb-3">
                <label for="feature" class="form-label ol-form-label">{{ get_phrase('Features') }}</label>
                <div class="work-feature row">
                    @foreach ($features ?? [] as $key => $feature)
                        <div class="col-md-3 mb-3"> {{-- 4 items per row (12/3 = 4 cols) --}}
                            <div class="feature-item">
                                <input class="form-check-input d-none" 
                                    name="feature[]" 
                                    type="checkbox" 
                                    value="{{ $feature->id ?? '' }}" 
                                    id="featureCheck{{ $key }}">
                                <label class="form-check-label w-100" onclick="sleep_feature_checked_add('featureCheck{{ $key }}')" for="featureCheck{{ $key }}">
                                    <div class="card mb-3 team-checkbox me-2">
                                        <div class="col-md-12 team-body feature-body">
                                            <div class="card-body py-2 px-2 ms-1">
                                                <span>{{ $feature->name ?? '' }}</span>
                                            </div>
                                            <div class="checked d-none" id="sleep-feature-checkedfeatureCheck{{ $key }}">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mb-3">
                <label for="property_type" class="form-label ol-form-label">{{ get_phrase('Property Type') }}</label>
                <div class="work-feature row">
                    @php
                        $property_types = ['Corner property','Large property','Ventilation','Furnished','Unfurnished',
                                    'Semi-Furnished','Female','Male','Non Attached','Attached','Hall',
                                    'Short Term','Long Term'];
                    @endphp

                    @foreach ($property_types as $key => $type)
                        <div class="col-md-3 mb-3"> 
                            <div class="feature-item">
                                <input class="form-check-input d-none" 
                                    name="room_type[]" 
                                    type="checkbox" 
                                    value="{{ $type }}" 
                                    id="propertyTypeCheck{{ $key }}">
                                <label class="form-check-label w-100" onclick="sleep_feature_checked_add('propertyTypeCheck{{ $key }}')" for="propertyTypeCheck{{ $key }}">
                                    <div class="card mb-3 team-checkbox me-2">
                                        <div class="col-md-12 team-body feature-body">
                                            <div class="card-body py-2 px-2 ms-1">
                                                <span>{{ $type }}</span>
                                            </div>
                                            <div class="checked d-none" id="sleep-feature-checkedpropertyTypeCheck{{ $key }}">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
    <button type="submit" class="btn ol-btn-primary fs-14">{{ get_phrase('Create') }}</button>      
</form>    

@elseif ($action == 'edit')
<form action="{{ route('admin.update.listing.property',['prefix'=>$user_prefix,'id'=>$id,'property_id'=>$property->id ?? 0]) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="title" class="form-label ol-form-label">{{ get_phrase('Property Title') }}</label>
                <input type="text" name="title" id="title" class="form-control ol-form-control" value="{{ $property->title ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="person" class="form-label ol-form-label">{{ get_phrase('Number of persons') }}</label>
                <input type="number" name="person" id="person" class="form-control ol-form-control" value="{{ $property->person ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="child" class="form-label ol-form-label">{{ get_phrase('Number of Child') }}</label>
                <input type="number" name="child" id="child" class="form-control ol-form-control" value="{{ $property->child ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label ol-form-label">{{ get_phrase('Property Price') }}</label>
                <input type="number" name="price" id="price" class="form-control ol-form-control" value="{{ $property->price ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label ol-form-label">{{ get_phrase('Property Images') }}</label>
                <input type="file" name="image[]" id="image" class="form-control ol-form-control" multiple>
            </div>
        </div>
       <div class="col-sm-6">
    {{-- Features --}}
    <div class="mb-3">
        <label for="feature" class="form-label ol-form-label">{{ get_phrase('Features') }}</label>
        @php
            $propertyFeatures = [];
            if (isset($property->feature)) {
                if (is_array($property->feature)) {
                    $propertyFeatures = $property->feature; // already array
                } elseif (is_string($property->feature) && $property->feature != 'null') {
                    $propertyFeatures = json_decode($property->feature, true) ?? [];
                }
            }
        @endphp

        <div class="work-feature">
            @foreach ($features ?? [] as $key => $feature)
                <div class="feature-item">
                    <input 
                        class="form-check-input d-none" 
                        name="feature[]" 
                        type="checkbox" 
                        value="{{ $feature->id ?? '' }}" 
                        id="flexCheck{{ $key }}"
                        @if(in_array($feature->id, $propertyFeatures)) checked @endif
                    >
                    <label class="form-check-label w-100" onclick="sleep_feature_checked('{{ $key }}')" for="flexCheck{{ $key }}">
                        <div class="card mb-3 team-checkbox me-2">
                            <div class="col-md-12 team-body feature-body">
                                <div class="card-body py-2 px-2 ms-1">
                                    <span>{{ $feature->name ?? '' }}</span>
                                </div>
                                <div class="checked @if(in_array($feature->id, $propertyFeatures)) @else d-none @endif" id="sleep-feature-checked{{ $key }}">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mb-3">
        <label for="property_type" class="form-label ol-form-label">{{ get_phrase('Property Type') }}</label>
        <div class="work-feature row">
            @php
                $property_types = [
                    'Corner Property','Large Property','Ventilation','Furnished','Unfurnished',
                    'Semi-Furnished','Female','Male','Non Attached','Attached','Hall',
                    'Short Term','Long Term'
                ];
                $savedpropertyTypes = [];
                if (!empty($property->room_type)) {
                    if (is_array($property->room_type)) {
                        $savedpropertyTypes = $property->room_type;
                    } elseif (is_string($property->room_type)) {
                        $savedpropertyTypes = array_map('trim', explode(',', $property->room_type));
                    }
                }
            @endphp

            @foreach ($property_types as $key => $type)
                <div class="col-md-3 mb-3"> 
                    <div class="feature-item">
                        <input class="form-check-input d-none" 
                            name="property_type[]" 
                            type="checkbox" 
                            value="{{ $type }}" 
                            id="propertyTypeCheck{{ $key }}"
                            @if(in_array($type, $savedpropertyTypes)) checked @endif
                        >
                        <label class="form-check-label w-100" onclick="sleep_feature_checked('propertyTypeCheck{{ $key }}')" for="propertyTypeCheck{{ $key }}">
                            <div class="card mb-3 team-checkbox me-2">
                                <div class="col-md-12 team-body feature-body">
                                    <div class="card-body py-2 px-2 ms-1">
                                        <span>{{ $type }}</span>
                                    </div>
                                    <div class="checked @if(in_array($type, $savedpropertyTypes)) @else d-none @endif" id="sleep-feature-checkedpropertyTypeCheck{{ $key }}">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

    </div>
    <button type="submit" class="btn ol-btn-primary fs-14">{{ get_phrase('Submit') }}</button>      
</form>  
@endif
<script>
"use strict";
function sleep_feature_checked(key) {
    var checkedBox = document.getElementById(key);
    var checkedDiv = document.getElementById('sleep-feature-checked-' + key);
    if (checkedBox && checkedDiv) {
        if (checkedBox.checked) {
            checkedDiv.classList.remove('d-none');
        } else {
            checkedDiv.classList.add('d-none');
        }
    }
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('input[type=checkbox][name="feature[]"], input[type=checkbox][name="property_type[]"]').forEach(function (el) {
        let checkedDiv = document.getElementById('sleep-feature-checked-' + el.id);
        if (el.checked && checkedDiv) {
            checkedDiv.classList.remove('d-none');
        }
    });
});
</script>
<script>
"use strict";
function sleep_feature_checked_add(key) {
    var checkedBox = document.getElementById(key);
    var checkedDiv = document.getElementById('sleep-feature-checked' + key);
    if (checkedBox && checkedDiv) {
        checkedDiv.classList.toggle('d-none');
    }
}
</script>

