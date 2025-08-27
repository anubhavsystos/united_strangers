@php $user_prefix = (user('is_agent') == 1) ? 'agent' : 'customer'; @endphp

<div class="offcanvas-lg offcanvas-start ca-offcanvas" tabindex="-1" id="user-sidebar-offcanvas" aria-labelledby="user-sidebar-offcanvasLabel">
    <div class="offcanvas-header ca-offcanvas-header pb-3 cap-border-bottom mx-2 d-block">
        <div class="d-flex align-items-center gap-10px">
            <div class="circle-img-50px">
                <img src="{{ get_user_image('users/' . user('image')) }}" alt="">
            </div>
            <div>
                <h2 class="in-title-14px">{{ user('name') }}</h2>
                <p class="in-subtitle-14px text-break">{{ user('email') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close ca-btn-close d-block d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#user-sidebar-offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body ca-offcanvas-body">
        <div class="w-100 pt-3">
            <div class="mb-3">
                <h3 class="in-title-14px mb-2 cap-sidebar-title">{{ get_phrase('My Customer Panel') }}</h3>
                <nav>
                    <ul>                       
                            <li class="sidebar-nav-item">
                                <a href="{{ route('customer.appointment') }}" class="sidebar-nav-link {{ $active == 'userAppointment' ? 'active' : '' }}">
                                    <span class="d-flex align-items-start mt-1px gap-6px">
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
                </nav>
            </div>
            <div class="d-flex justify-content-center">
                <a href="{{ route('logout') }}" class="btn cap-btn-primary w-100">
                    <img src="{{ asset('assets/frontend/images/icons/logout-left-white-20.svg') }}" alt="icon">
                    <span>{{ get_phrase('Logout') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
