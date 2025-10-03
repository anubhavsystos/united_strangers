<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.seo')
    @include('layouts.include_top')
    @stack('css')
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<style>
    .image_size{
        height: 100px;
    }
</style>
<body>

   <header class="{{ request()->is('work') ? '' : 'header-section' }} mb-2">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="at-home-menu-wrap">
                        <div>
                            <a href="" class="d-block atn-logo">
                                @if (get_frontend_settings('light_logo'))
                                    <img src="{{ asset('uploads/logo/' . get_frontend_settings('light_logo')) }}" alt="" class="image_size radious-15px px-2 py-2 light-logo-preview h-77">
                                @endif
                            </a>
                        </div>
                        <div class="at-home-menu-button ca-home-menu-button">
                            <!-- offcanvas menu start -->
                            <div class="offcanvas-xl offcanvas-end at-home-offcanvas" tabindex="-1" id="offcanvasResponsive" aria-labelledby="offcanvasResponsiveLabel">
                                <div class="offcanvas-header">
                                    <div>
                                        <a href="" class="d-block atn-logo">
                                            @if (get_frontend_settings('light_logo'))
                                                <img src="{{ asset('uploads/logo/' . get_frontend_settings('light_logo')) }}"  alt="" class="image_size radious-15px px-2 py-2 light-logo-preview h-77 ">
                                            @endif
                                        </a>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasResponsive" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <nav>
                                        <ul class="at-home-navbar-nav bt-home-navbar-nav">
                                            <li><a href="{{ route('listing.view', ['type' => 'sleep', 'view' => 'grid']) }}" class="at-home-nav-link {{ request()->routeIs('listing.view') ? 'active' : '' }}">{{ get_phrase('Sleep') }}</a></li>
                                            <li><a href="{{ route('listing.view', ['type' => 'work', 'view' => 'grid']) }}" class="at-home-nav-link {{ request()->routeIs('listing.view') ? 'active' : '' }}">{{ get_phrase('Work') }}</a></li>
                                            <li><a href="{{ route('listing.view', ['type' => 'play', 'view' => 'grid']) }}" class="at-home-nav-link {{ request()->routeIs('listing.view') ? 'active' : '' }}">{{ get_phrase('Play') }}</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <!-- offcanvas menu end -->
                            <div class="at-home-search-login-button">
                                <div class="at-home-nav-search ca-home-nav-search d-none d-md-block">


                                </div>
                                <!-- For Login -->
                                
                                 
                                @if (user('role') == 1)
                                    <div class="dropdown at-user-dropdown">
                                        <button class="btn user-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="{{ user('image') ? asset('uploads/users/' . user('image')) : asset('image/user.jpg') }}" alt="">
                                        </button>
                                        <div class="dropdown-menu user-dropdown-menu">
                                            <ul class="user-dropdown-group">

                                                <li><a class="user-dropdown-item" href="{{ route('admin.dashboard') }}">
                                                        <span class="icon mt-2px">
                                                            <svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="14" height="14">
                                                                <path d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z" />
                                                                <path d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z" />
                                                                <path d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z" />
                                                                <path d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z" />
                                                            </svg>
                                                        </span>
                                                        <span class="mt-2px">{{ get_phrase('Dashboard') }}</span>
                                                    </a></li>
                                            </ul>
                                            <div class="px-10px py-12px">
                                                <a href="{{ route('logout') }}" class="user-dropdown-item">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.1599 14.8467L10.0733 14.8467C7.11326 14.8467 5.6866 13.68 5.43993 11.0667C5.41326 10.7933 5.61326 10.5467 5.89326 10.52C6.15993 10.4933 6.41326 10.7 6.43993 10.9733C6.63326 13.0667 7.61993 13.8467 10.0799 13.8467L10.1666 13.8467C12.8799 13.8467 13.8399 12.8867 13.8399 10.1733L13.8399 5.82665C13.8399 3.11332 12.8799 2.15332 10.1666 2.15332L10.0799 2.15332C7.6066 2.15332 6.61993 2.94665 6.43993 5.07999C6.4066 5.35332 6.17326 5.55999 5.89326 5.53332C5.61326 5.51332 5.41326 5.26665 5.43326 4.99332C5.65993 2.33999 7.09326 1.15332 10.0733 1.15332L10.1599 1.15332C13.4333 1.15332 14.8333 2.55332 14.8333 5.82665L14.8333 10.1733C14.8333 13.4467 13.4333 14.8467 10.1599 14.8467Z" fill="#99A1B7" />
                                                        <path d="M10 8.5L2.41333 8.5C2.14 8.5 1.91333 8.27333 1.91333 8C1.91333 7.72667 2.14 7.5 2.41333 7.5L10 7.5C10.2733 7.5 10.5 7.72667 10.5 8C10.5 8.27333 10.2733 8.5 10 8.5Z" fill="#99A1B7" />
                                                        <path d="M3.89988 10.7333C3.77321 10.7333 3.64655 10.6866 3.54655 10.5866L1.31321 8.35331C1.11988 8.15998 1.11988 7.83998 1.31321 7.64664L3.54655 5.41331C3.73988 5.21998 4.05988 5.21998 4.25321 5.41331C4.44655 5.60664 4.44655 5.92664 4.25321 6.11998L2.37321 7.99998L4.25321 9.87998C4.44655 10.0733 4.44655 10.3933 4.25321 10.5866C4.15988 10.6866 4.02655 10.7333 3.89988 10.7333Z" fill="#99A1B7" />
                                                    </svg>
                                                    <span class="mt-2px">{{ get_phrase('Log Out') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(user('role') == 2)
                                      <div class="dropdown at-user-dropdown">
                                        <button class="btn user-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="{{ user('image') ? asset('uploads/users/' . user('image')) : asset('image/user.jpg') }}" alt="">
                                        </button>
                                        <div class="dropdown-menu user-dropdown-menu">
                                            <ul class="user-dropdown-group">
                                         
                                                <!-- <li class="sidebar-nav-item">
                                                    <a href="{{ route('customer.wishlist') }}" class="user-dropdown-item fill-none">
                                                        <span class="d-flex align-items-center mt-1px gap-6px">
                                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.465 15.6075C9.21 15.6975 8.79 15.6975 8.535 15.6075C6.36 14.865 1.5 11.7675 1.5 6.51745C1.5 4.19995 3.3675 2.32495 5.67 2.32495C7.035 2.32495 8.2425 2.98495 9 4.00495C9.7575 2.98495 10.9725 2.32495 12.33 2.32495C14.6325 2.32495 16.5 4.19995 16.5 6.51745C16.5 11.7675 11.64 14.865 9.465 15.6075Z" stroke="#99A1B7" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                            <span class="mt-1px">{{ get_phrase('Wishlist') }}</span>
                                                        </span>
                                                        <span class="badge-secondary mt-1px">
                                                            @php
                                                                $wis = App\Models\Wishlist::where('user_id', user('id'))->get();
                                                            @endphp
                                                            {{ count($wis) }}
                                                        </span>
                                                    </a>
                                                </li> -->
                                                <li class="sidebar-nav-item">
                                                    <a href="{{ route('customer.appointment') }}" class="user-dropdown-item fill-none">
                                                        <span class="d-flex align-items-center mt-1px gap-6px">
                                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M6.98242 11.025L8.10742 12.15L11.1074 9.15002" stroke="#99A1B7" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M7.5 4.5H10.5C12 4.5 12 3.75 12 3C12 1.5 11.25 1.5 10.5 1.5H7.5C6.75 1.5 6 1.5 6 3C6 4.5 6.75 4.5 7.5 4.5Z" stroke="#99A1B7" stroke-width="1.4" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M12 3.01501C14.4975 3.15001 15.75 4.07251 15.75 7.50001V12C15.75 15 15 16.5 11.25 16.5H6.75C3 16.5 2.25 15 2.25 12V7.50001C2.25 4.08001 3.5025 3.15001 6 3.01501" stroke="#99A1B7" stroke-width="1.4" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                            <span class="mt-1px">{{ get_phrase('Appointment') }}</span>
                                                        </span>
                                                        <span class="badge-secondary mt-1px">
                                                            @php
                                                                $appoint = App\Models\Appointment::where('customer_id', user('id'))->get();
                                                            @endphp
                                                            {{ count($appoint) }}
                                                        </span>
                                                    </a>
                                                </li>                                              
                                            </ul>
                                            <div class="px-10px py-12px">
                                                <a href="{{ route('logout') }}" class="user-dropdown-item">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.1599 14.8467L10.0733 14.8467C7.11326 14.8467 5.6866 13.68 5.43993 11.0667C5.41326 10.7933 5.61326 10.5467 5.89326 10.52C6.15993 10.4933 6.41326 10.7 6.43993 10.9733C6.63326 13.0667 7.61993 13.8467 10.0799 13.8467L10.1666 13.8467C12.8799 13.8467 13.8399 12.8867 13.8399 10.1733L13.8399 5.82665C13.8399 3.11332 12.8799 2.15332 10.1666 2.15332L10.0799 2.15332C7.6066 2.15332 6.61993 2.94665 6.43993 5.07999C6.4066 5.35332 6.17326 5.55999 5.89326 5.53332C5.61326 5.51332 5.41326 5.26665 5.43326 4.99332C5.65993 2.33999 7.09326 1.15332 10.0733 1.15332L10.1599 1.15332C13.4333 1.15332 14.8333 2.55332 14.8333 5.82665L14.8333 10.1733C14.8333 13.4467 13.4333 14.8467 10.1599 14.8467Z" fill="#99A1B7" />
                                                        <path d="M10 8.5L2.41333 8.5C2.14 8.5 1.91333 8.27333 1.91333 8C1.91333 7.72667 2.14 7.5 2.41333 7.5L10 7.5C10.2733 7.5 10.5 7.72667 10.5 8C10.5 8.27333 10.2733 8.5 10 8.5Z" fill="#99A1B7" />
                                                        <path d="M3.89988 10.7333C3.77321 10.7333 3.64655 10.6866 3.54655 10.5866L1.31321 8.35331C1.11988 8.15998 1.11988 7.83998 1.31321 7.64664L3.54655 5.41331C3.73988 5.21998 4.05988 5.21998 4.25321 5.41331C4.44655 5.60664 4.44655 5.92664 4.25321 6.11998L2.37321 7.99998L4.25321 9.87998C4.44655 10.0733 4.44655 10.3933 4.25321 10.5866C4.15988 10.6866 4.02655 10.7333 3.89988 10.7333Z" fill="#99A1B7" />
                                                    </svg>
                                                    <span class="mt-2px">{{ get_phrase('Log Out') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('login') }}" class="login at-home-nav-link">{{ get_phrase('Login') }}</a>
                                @endif
                                <!-- <a href="#" class="btn ca-btn-dark at-home-listing-btn d-flex align-items-center gap-2">
                                    <img src="{{ asset('assets/frontend/images/icons/plus-white-8.svg') }}" alt="">
                                    <span>{{ get_phrase('Add Listing') }}</span>
                                </a> -->
                                <button class="btn at-home-menu-btn ca-home-menu-btn d-xl-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasResponsive" aria-controls="offcanvasResponsive">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 20 20" xml:space="preserve" class="">
                                        <g>
                                            <path d="M21 7H8a1 1 0 0 1 0-2h13a1 1 0 0 1 0 2zm1 5a1 1 0 0 0-1-1H3a1 1 0 0 0 0 2h18a1 1 0 0 0 1-1zm0 6a1 1 0 0 0-1-1h-9a1 1 0 0 0 0 2h9a1 1 0 0 0 1-1z" fill="#6c1cff" opacity="1" data-original="#000000" class=""></path>
                                        </g>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="flex justify-start space-x-6 bg-white border-t border-black px-6 py-2 text-sm">
        <a href="{{ route('blogs')}}" class="hover:underline"> Blogs </a>
        <a href="{{ route('contact-us')}}" class="hover:underline"> Contact us </a>
        <a href="{{ route('privacy-policy')}}" class="hover:underline"> Privacy-Policy </a>
        <a href="{{ route('refund-policy')}}" class="hover:underline"> Refund Policy</a>
        <a href="{{ route('terms-and-condition')}}" class="hover:underline">Terms and Condition</a>
      </div>
    </header> 
    @yield('frontend_layout')
    @include('layouts.include_bottom')
    @include('layouts.toaster')
    @stack('js')   

</body>
</html>
