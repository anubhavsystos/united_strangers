<!-- Header -->
<div class="ol-header print-d-none d-flex align-items-center justify-content-between py-2 ps-3">
    <div class="header-title-menubar d-flex align-items-start flex-wrap mt-md-1">
        <div class="main-header-title d-flex align-items-start pb-sm-0 h-auto p-0">
            <button class="menu-toggler sidebar-plus">
                <span class="fi-rr-menu-burger"></span>
            </button>
            <h1 class="page-title ms-2 fs-18px d-flex flex-column row-gap-0">
                <span> United Strangers </span>
                <p class="text-12px fw-400 d-none d-lg-none d-xl-inline-block mt-1">{{ get_phrase('Admin Panel') }}</p>
            </h1>
        </div>
    </div>
    <div class="header-content-right d-flex align-items-center justify-content-end">      
        <!-- Profile -->
        <div class="header-dropdown-md">
            <button class="header-dropdown-toggle-md" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-profile-sm">
                    <img src="{{ user('image') ? asset('uploads/users/' . user('image')) : asset('image/user.jpg') }}" alt="">
                </div>
            </button>
            <div class="header-dorpdown-menu-md p-3">
                <div class="d-flex column-gap-2 mb-12px pb-12px ol-border-bottom-2">
                    <div class="user-profile-sm">
                        <img src="{{ user('image') ? asset('uploads/users/' . user('image')) : asset('image/user.jpg') }}" alt="">
                    </div>
                    <div>
                        <h6 class="title fs-12px mb-2px"> {{user('name')}} </h6>
                        <p class="sub-title fs-12px"> {{get_phrase('Admin')}} </p>
                    </div>
                </div>
                <ul class="mb-12px">
                    <li class="dropdown-list-1"><a class="dropdown-item-1" href="{{ route('admin.profile')}}"> {{get_phrase('My Profile')}} </a></li>
                    <li class="dropdown-list-1"><a class="dropdown-item-1" href="{{ route('logout') }}"> {{get_phrase('Sign Out')}} </a></li>
                </ul>
            </div>
        </div>
    </div>
</div>