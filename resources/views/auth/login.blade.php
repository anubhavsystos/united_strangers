@extends('layouts.frontend')
@push('title', get_phrase('Login'))
@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

@endpush

@section('frontend_layout')

<style>
/* Google & OTP Buttons Styling */
.google-btn, .otp-btn {
    display: flex;
    align-items: center;
    text-decoration: none;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    overflow: hidden;
    font-family: Roboto, sans-serif;
    height: 40px;
}
.google-btn {
    background: #4285f4;
    color: white;
}
.google-icon-wrapper {
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
}
.google-icon { width: 18px; height: 18px; }
.btn-text { margin: 0; padding: 0 15px; font-size: 14px; font-weight: 500; }
.google-btn:hover, .otp-btn:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.3); }

.otp-btn {
    background: #34A853; 
    color: white;
}
.otp-icon-wrapper {
    /* background: white; */
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
    font-size: 16px;
}
</style>

<section class="mb-60px mt-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-5 col-lg-6">
                <div>
                    <div class="mb-50px">
                        <h3 class="mb-3 in-title2-24px">{{ get_phrase('Get Started Now') }}</h3>
                        <p class="in-subtitle3-16px lh-base">{{ get_phrase('Enter your credentials to access your account') }}</p>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label ua-form-label mb-3">{{ get_phrase('Email') }}</label>
                            <input type="email" class="form-control ua-form-control @error('email') is-invalid @enderror" id="email" value="anubhavjain.systos@gmail.com" name="email" placeholder="username@gmail.com" autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-20px">
                            <div class="d-flex justify-content-between mb-3">
                                <label for="password" class="form-label ua-form-label">{{ get_phrase('Password') }}<span>*</span></label>
                                <a href="{{ route('password.request') }}" class="ua-link">{{ get_phrase('Forget your password') }} ?</a>
                            </div>
                            <input type="password" class="form-control ua-form-control @error('password') is-invalid @enderror" id="password" value="systos!@#5" name="password" required placeholder="Enter password">
                            @error('password')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <button type="submit" class="btn ua-btn-dark w-100 mb-3">{{ get_phrase('Login') }}</button>
                        <p class="text-center">{{ get_phrase('Don\'t have an account') }}? <a href="{{ route('register') }}" class="fw-semibold ua-link">{{ get_phrase('Sign up') }}</a></p>
                    </form>

                    <div class="login-buttons mt-4">
                        <div class="d-flex gap-2" style="justify-content: center;">
                            <!-- <a href="{{ route('login.google') }}" class="google-btn flex-fill">
                                <div class="google-icon-wrapper">
                                    <img class="google-icon" src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo">
                                </div>
                                <p class="btn-text"><b>Sign in with Google</b></p>
                            </a> -->
                            <div class="col-xl-5 col-lg-6">
                                <button type="button" class="otp-btn flex-fill" data-bs-toggle="modal" data-bs-target="#otpModal">
                                    <div class="otp-icon-wrapper"><i class="ml-1 fas fa-mobile-alt"></i></div>
                                    <span class="btn-text"><b>Login with OTP</b></span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-7 col-lg-6">
                    <div class="d-flex justify-content-center justify-content-lg-end">
                        <div class="ua-banner-slider-wrap">
                            @php
                                $homeBanner = json_decode(get_frontend_settings('mother_homepage_banner'), true);
                            @endphp
                            <!-- Swiper -->
                            <div class="swiper ua-slider">
                                <div class="swiper-wrapper">
                                    @if (!empty($homeBanner) && is_array($homeBanner))
                                        @foreach ($homeBanner as $banner)
                                            <div class="swiper-slide">
                                                <div class="ua-slider-banner">
                                                    <img class="banner" src="{{ asset('uploads/mother_homepage_banner/' . $banner['image']) }}" alt="banner">
                                                </div>
                                                <div class="ua-slider-content">
                                                    <img class="mb-3" src="{{ asset('assets/frontend/images/login/ua-star-shape.svg') }}" alt="shape">
                                                    <h1 class="ua-title-36px text-white mb-3">{{ $banner['title'] }}</h1>
                                                    <p class="in-subtitle-16px text-white ua-slider-subtitle-margin">{{ $banner['description'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="swiper-slide">
                                            <div class="ua-slider-banner">
                                                <img class="banner" src="{{ asset('assets/frontend/images/login/ua-login-banner1.webp') }}" alt="banner">
                                            </div>
                                            <div class="ua-slider-content">
                                                <img class="mb-3" src="{{ asset('assets/frontend/images/login/ua-star-shape.svg') }}" alt="shape">
                                                <h1 class="ua-title-36px text-white mb-3"></h1>
                                                <p class="in-subtitle-16px text-white ua-slider-subtitle-margin"></p>

                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>

<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <h5>Login with OTP</h5>
            <input type="text" id="phone" name="phone" maxlength="10" class="form-control" placeholder="Enter Phone Number">
<small id="phoneError" class="text-danger"></small>
            
            <button id="sendOtpBtn" class="btn btn-primary w-100">Send OTP</button>
            
            <div id="otpSection" style="display:none;" class="mt-3">
                <input type="text" id="otp" class="form-control mb-2" placeholder="Enter OTP">
                <button id="verifyOtpBtn" class="btn btn-success w-100">Verify OTP</button>
            </div>

            <p id="otpMessage" class="mt-2 text-center"></p>
        </div>
    </div>
</div>

<script>
document.getElementById('sendOtpBtn').addEventListener('click', function(){
    let phone = document.getElementById('phone').value;
    fetch('{{ route("send.otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ phone: phone })
    }).then(res => res.json()).then(data => {
        document.getElementById('otpMessage').innerText = data.message;
        if(data.status){
            document.getElementById('otpSection').style.display = 'block';
            console.log("Test OTP:", data.otp); // Testing ke liye console me OTP
        }
    });
});


$('#verifyOtpBtn').on('click', function() {
    let otp = $('#otp').val(); // ID match karo
    let url = '{{ route("verify.otp") }}'; 

    fetch(url, { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({ otp: otp })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            window.location.href = data.redirect; // redirect yaha hoga
        } else {
            alert(data.message);
        }
    })
    .catch(err => console.error(err));
});


$('#phone').on('input', function () {
    let phone = $(this).val().replace(/\D/g, ''); // only digits
    $(this).val(phone); // non-numeric remove

    if (phone.length === 10) {
        $.ajax({
            url: '{{ route("check.phone") }}',
            method: 'POST',
            data: {
                phone: phone,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (res) {
                console.log(res)
                if (!res.exists) {
                    $('#phoneError').text('This phone number is not registered');
                } else {
                    $('#phoneError').text('');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                $('#phoneError').text('Server error occurred');
            }
        });
    } else {
        $('#phoneError').text('');
    }
});

</script>

@endsection
