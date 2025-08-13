@extends('layouts.admin')
@section('title', get_phrase('System Settings'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-20px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Website Settings') }}
            </h4>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-3">
    <div class="col-xl-12">
        <div class="ol-card p-4">
            <div class="ol-card-body">
                <div class="col-md-12 pb-3">
                    <ul class="nav nav-tabs eNav-Tabs-custom eTab" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="cHome-tab" data-bs-toggle="tab" data-bs-target="#cHome"
                                type="button" role="tab" aria-controls="cHome" aria-selected="true">
                                {{ get_phrase('Frontend Settings') }}
                                <span></span>
                            </button>
                        </li>
                                              
                    </ul>
                    <div class="tab-content eNav-Tabs-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="cHome" role="tabpanel"
                            aria-labelledby="cHome-tab">
                            <div class="tab-pane show active mt-5" id="frontendsettings">
                                <form action="{{route('admin.website-setting-update')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="frontend_settings">
                                
                                    

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label" for="about_us">{{ get_phrase('About us') }}</label>
                                        <textarea name="about_us" id = "about_us" class="form-control ol-form-control text_editor" rows="5">{{get_frontend_settings('about_us')}}</textarea>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label" for="terms_and_condition">{{ get_phrase('Terms and condition') }}</label>
                                        <textarea name="terms_and_condition" id ="terms_and_condition" class="form-control ol-form-control text_editor" rows="5">{{ get_frontend_settings('terms_and_condition') }}</textarea>
                                    </div>
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label" for="privacy_policy">{{ get_phrase('Privacy policy') }}</label>
                                        <textarea name="privacy_policy" id = "privacy_policy" class="form-control ol-form-control text_editor" rows="5">{{ get_frontend_settings('privacy_policy') }}</textarea>
                                    </div>
                                
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label" for="refund_policy">{{ get_phrase('Refund policy') }}</label>
                                        <textarea name="refund_policy" id = "refund_policy" class="form-control ol-form-control text_editor" rows="5">{{ get_frontend_settings('refund_policy') }}</textarea>
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <button type="submit" class="btn ol-btn-primary ">{{get_phrase('Update Settings')}}</button>
                                    </div>
    
                                 </form>
                                 
                            </div>
                        </div>
                        
                     
                       
                        <div class="tab-pane fade" id="cSettings" role="tabpanel" aria-labelledby="cSettings-tab">
                        
                        </div>
                        <div class="tab-pane fade" id="contact_information" role="tabpanel"
                            aria-labelledby="contact_information-tab">
                          
                        </div>
                        <div class="tab-pane fade" id="recaptcha" role="tabpanel" aria-labelledby="recaptcha-tab">
                            
                        </div>
                        
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div>
    </div>
</div>

<link href="{{asset('plugin/summernote/summernote-lite.min.css')}}" rel="stylesheet">
<script src="{{asset('plugin/summernote/summernote-lite.min.js')}}"></script> 
<script type="text/javascript">
    "use strict";
    // Generalized function to handle image preview for light, dark, and favicon logos
function handleImagePreview(input, previewSelector) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector(previewSelector).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}




$('.text_editor').summernote({
        placeholder: "{{get_phrase('Write description')}}",
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']]
        ]
      });
</script>
@endsection