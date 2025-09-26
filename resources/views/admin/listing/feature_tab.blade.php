<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fs-16px title mb-3"> {{get_phrase('Add Some Listing Feature')}} </h5>
    <a href="javascript:void(0);" onclick="modal('modal-md', '{{route('admin.amenities.add',['prefix' =>'admin', 'type'=>$segment_type,'item'=>'feature','page'=>'listing','listing_id'=>$listing->id])}}', '{{get_phrase('Add New Service')}}')" class="btn ol-btn-primary fs-14px"> {{get_phrase('Add Feature')}} </a>
</div>  
<div class="work-feature">
    @if(count($features) != 0)
        @foreach ($features as $key => $feature)
            <div class="feature-item position-relative d-inline-block me-2 mb-2">
                @php $user_prefix = (user('role') == 1) ? 'admin':'agent'; @endphp
                <a class="position-absolute top-0 end-0 m-1 text-danger fs-14px"
                   style="z-index: 2;"
                   onclick="delete_modal('{{route('admin.amenities.delete',['prefix' => $user_prefix, 'id'=>$feature->id])}}')"
                   href="javascript:void(0);">
                    <i class="fas fa-trash-alt"></i>
                </a>

                <input class="form-check-input d-none" name="feature[]" type="checkbox" value="{{$feature->id}}" id="flexCheckDefau{{$key}}" 
                       @if($listing->feature && $listing->feature != 'null' && in_array($feature->id, json_decode($listing->feature))) checked @endif>

                <label class="form-check-label w-100" onclick="feature_select('{{$key}}')" for="flexCheckDefau{{$key}}">
                    <div class="card mb-0 team-checkbox text-center" style="width: 120px; height: 100px; position: relative;">
                        <div class="card-body p-2 d-flex flex-column justify-content-center align-items-center">
                            <div class="icon mb-1">
                                <img src="{{ asset($feature->image ? '/' . $feature->image : '/image/placeholder.png') }}" alt="" class="rounded" style="width:40px;height:40px;">
                            </div>
                            <span class="text-center fs-12px w-100"> {{$feature->name}} </span>
                        </div>
                        <div class="checked @if($listing->feature && $listing->feature != 'null' && in_array($feature->id, json_decode($listing->feature))) @else d-none @endif" id="feature-checked{{$key}}">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </label>
            </div>
        @endforeach
    @endif
</div>
