@extends('layouts.frontend')
@push('title', get_phrase('Customer Appointment'))
@push('meta')@endpush
@section('frontend_layout')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
    .table > tbody.ca-tbody {
	vertical-align: inherit;
}
.read-more {
	font-size: 14px;
	font-weight: 500;
}
.in-subtitle-14px{
  color: #242D47 !important;
  font-weight: 500;
}
.ca-tr .text-center .dropdown {
	height: 24px;
	width: 24px;
	margin: auto;
}
.button_font {
    font-size: 10px;
}
</style>
    <!-- Start Main Area -->
    <section class="ca-wraper-main mb-90px mt-4">
        <div class="container">
            <div class="row gx-20px">
                <div class="col-lg-4 col-xl-3">
                    @include('user.navigation')
                </div>
                <div class="col-lg-8 col-xl-9">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start gap-12px flex-column flex-lg-row w-100">
                        <h1 class="in-title-16px">{{ get_phrase('appointment') }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb cap-breadcrumb">
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="">{{ get_phrase('Home') }}</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item active" aria-current="page">{{ get_phrase('appointment') }}</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="d-flex gap-3 mt-3 flex-wrap">
                        <div class="d-flex align-items-center gap-2 p-2 rounded" style="background-color:#e3f2fd;">
                            <i class="fas fa-wallet text-primary"></i>
                            <span><strong>{{ currency(auth()->user()->wallet_price ?? 0) }}</strong> Wallet</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2 rounded" style="background-color:#fff3e0;">
                            <i class="fas fa-coins text-warning"></i>
                            <span><strong>{{ auth()->user()->coins_price ?? 0 }}</strong> Coins</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2 rounded" style="background-color:#e8f5e9;">
                            <i class="fas fa-gift text-success"></i>
                            <span><strong>{{ auth()->user()->rewards_price ?? 0 }}</strong> Rewards</span>
                        </div>
                    </div>


                    <div class="ca-content-card table-responsive mt-2 pb-1">                        
                        <table id="appointments-table" class="table ca-table ca-table-width">
                            <thead class="ca-thead">                              
                                <tr class="ca-tr">
                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Id')}}</th>                                                                
                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Customer')}}</th>
                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Contact')}}</th>
                                    
                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Listing Name')}}</th>                                                                                             
                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Segment type')}}</th> 
                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Property')}}</th>

                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Total Price')}}</th>
                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Person')}}</th>
                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Booking Date')}}</th>

                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Details')}}</th>                                                             
                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Status')}}</th>                                                                      
                                    <th scope="col" class="ca-title-14px ca-text-dark">{{get_phrase('Payment')}}</th>                                                                
                                </tr>
                            </thead>
                            <tbody class="ca-tbody">
                                 @foreach ($appointments as $key => $appointment) 
                                    <tr class="ca-tr">
                                        <td ><p class="ca-subtitle-14px ca-text-dark mb-2"> {{++$key}}.</p></td>  
                                        <td class="min-w-140px">                                                                        
                                            <p class="ca-subtitle-14px ca-text-dark mb-2 line-2"> {{isset($appointment['customer_name']) ? $appointment['customer_name'] : ''}}</p>
                                            <p class="ca-subtitle-14px ca-text-dark mb-2 line-1"> {{!empty($appointment['name']) ? "For :" . $appointment['name'] : ''}}</p>
                                        </td>
                                        <td class="min-w-140px">
                                            <div class="align-items-center gap-2"><p >{{isset($appointment['customer_phone']) ? $appointment['customer_phone'] : ''}} </p>
                                            <p class="ca-subtitle-14px ca-text-dark mb-2 line-1"> {{isset($appointment['phone']) ?  $appointment['phone'] : ''}}</p></div>
                                        </td>
                                         <td class="min-w-140px">
                                            <div class="d-flex align-items-center gap-2"><p class="badge-dark">{{isset($appointment['title']) ? $appointment['title'] : ''}} </p></div>
                                        </td>
                                        <td class="min-w-140px">
                                            <div class="d-flex align-items-center gap-2"><p class="badge-dark">{{isset($appointment['listing_type']) ? $appointment['listing_type'] : ''}} </p></div>
                                        </td> 
                                        <td class="min-w-140px">
                                            @php
                                                $property = [];
                                                if (!empty($appointment['room_name'])) {
                                                    $roomNames = is_array($appointment['room_name'])
                                                        ? $appointment['room_name']
                                                        : explode(',', $appointment['room_name']);
                                                    $property = array_merge($property, $roomNames);
                                                }

                                                if (!empty($appointment['menu_summary'])) {
                                                    $menuItems = is_array($appointment['menu_summary'])
                                                        ? $appointment['menu_summary']
                                                        : explode(',', $appointment['menu_summary']);
                                                    $property = array_merge($property, $menuItems);
                                                }
                                                $property = array_filter(array_map('trim', $property));
                                            @endphp

                                            <div class="d-flex flex-column">
                                                @foreach(array_slice($property, 0, 2) as $item)
                                                    <p class="badge-dark mb-1">{{ $item }}</p>
                                                @endforeach

                                                @if(count($property) > 2)
                                                    <a href="javascript:void(0);"
                                                    onclick="this.nextElementSibling.classList.toggle('d-none'); this.classList.add('d-none')">
                                                    ... Show more
                                                    </a>

                                                    <div class="d-none">
                                                        @foreach(array_slice($property, 2) as $item)
                                                            <p class="badge-dark mb-1">{{ $item }}</p>
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
                                                <p class="in-subtitle-14px">{{!empty($appointment['adults']) ? "Adults :". $appointment['adults'] : ''}} {{!empty($appointment['child']) ?  "Child :". $appointment['child'] : ''}}</p>
                                            </div> 
                                        </td>
                                        <td class="min-w-140px">
                                            <div class="d-flex align-items-center gap-2"><p class="badge-dark">{{isset($appointment['date']) ? $appointment['date'] : ''}} </p></div>
                                        </td>
                                        <td class="min-w-110px">
                                            <div class="d-flex gap-1 align-items-center">                                                
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
                                            @elseif($appointment['status'] == 3)
                                                <p class="badge-danger-light">{{get_phrase('Cancel')}}</p>
                                            @else
                                                <p class="badge-warning-light">{{get_phrase('Not start yet')}}</p>
                                            @endif
                                        </td>
                                                                                                        
                                       <td class="min-w-140px">
                                            @if($appointment['status'] == 1)
                                                <button type="button" class="btn btn-success button_font" disabled>
                                                    {{ get_phrase('Payment Complete') }}
                                                </button>
                                            @elseif($appointment['status'] == 2)
                                                <button type="button" class="btn btn-danger button_font" disabled>
                                                    {{ get_phrase('Cancelled') }}
                                                </button>
                                            @else
                                                @if($appointment['listing_type'] == 'work')
                                                    <button type="button" 
                                                            class="btn button_font btn-info visit-property-btn" 
                                                            data-appointment-id="{{ $appointment['id'] }}">
                                                        {{ get_phrase('Visit Property') }}
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                            class="btn btn-primary button_font pay-btn" 
                                                            data-id="{{ $appointment['id'] }}" 
                                                            data-price="{{ $appointment['total_price'] }}">
                                                        {{ get_phrase('Payment Pending') }}
                                                    </button>
                                                @endif

                                                <!-- Cancel Button triggers modal -->
                                                <button type="button" 
                                                        class="btn btn-outline-danger button_font mt-1 cancel-btn" 
                                                        data-id="{{ $appointment['id'] }}">
                                                    {{ get_phrase('Cancel Appointment') }}
                                                </button>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>                    
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">{{ get_phrase('Payment') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body text-center">
        <p>{{ get_phrase('Amount to Pay') }}:</p>
        <h3 id="payAmount">₹0</h3>
        <form method="POST" action="{{ route('customer.pay') }}">
            @csrf
            <input type="hidden" name="appointment_id" id="appointmentId">
            <input type="hidden" name="amount" id="paymentAmount">
            <button type="submit" class="btn btn-success mt-3">
                {{ get_phrase('Confirm & Pay') }}
            </button>
        </form>
      </div>
      
    </div>
  </div>
</div>
<!-- Visit Property / Thank You Modal -->
<div class="modal fade" id="visitPropertyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">

            <div class="mb-3">
                <img src="{{ asset('assets/frontend/images/icons/success.png') }}" 
                     alt="Success" 
                     width="100">
            </div>

            <h3 class="text-success mb-3">🎉 Thank You!</h3>
            <p class="mb-4">
                Your property visit has been scheduled successfully.
            </p>

            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                Close
            </button>

        </div>
    </div>
</div>
<!-- Cancel Appointment Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
           <form id="cancelForm"  method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ get_phrase('Cancel Appointment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ get_phrase('Are you sure you want to cancel this appointment?') }}</p>
                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Reason for cancellation') }}</label>
                        <textarea class="form-control" name="resion_msg" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ get_phrase('Close') }}
                    </button>
                    <button type="submit" class="btn btn-danger">
                        {{ get_phrase('Confirm Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- End Main Area -->
    @include('layouts.modal')


    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.read-more').forEach(function (button) {
                button.addEventListener('click', function () {
                    const parent = this.closest('.eMessage');
                    const shortText = parent.querySelector('.short-text');
                    const fullText = parent.querySelector('.full-text');
        
                    if (shortText.classList.contains('d-inline')) {
                        shortText.classList.remove('d-inline');
                        shortText.classList.add('d-none');
                        fullText.classList.remove('d-none');
                        fullText.classList.add('d-inline');
                        this.textContent = '{{ get_phrase("Show Less") }}';
                    } else {
                        shortText.classList.remove('d-none');
                        shortText.classList.add('d-inline');
                        fullText.classList.remove('d-inline');
                        fullText.classList.add('d-none');
                        this.textContent = '{{ get_phrase("Read More") }}';
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.pay-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    let price = btn.getAttribute('data-price');
                    let id = btn.getAttribute('data-id');

                    document.getElementById('payAmount').innerText = "₹" + price;
                    document.getElementById('paymentAmount').value = price;
                    document.getElementById('appointmentId').value = id;

                    let modal = new bootstrap.Modal(document.getElementById('paymentModal'));
                    modal.show();
                });
            });
        });
    </script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.visit-property-btn').forEach(function(btn){
        btn.addEventListener('click', function() {
            let appointmentId = btn.getAttribute('data-appointment-id');

            // Optional: send AJAX to mark as visited or record action
            // Example: axios.post('/customer/visitProperty', { id: appointmentId })

            // Open modal
            let modal = new bootstrap.Modal(document.getElementById('visitPropertyModal'));
            modal.show();
        });
    });
});
</script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".cancel-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            let id = this.getAttribute("data-id");

            // Blade will render the correct base URL for the route
            let url = @json(route('customer.cancelappointment', ['id' => ':id']));

            // Replace the placeholder with the actual ID
            url = url.replace(':id', id);

            let form = document.getElementById("cancelForm");
            form.action = url;

            new bootstrap.Modal(document.getElementById("cancelModal")).show();
        });
    });
});
</script>
<script>
$(document).ready(function() {
    $('#appointments-table').DataTable({
        "paging": true,          
        "lengthChange": true,    
        "searching": true,       
        "ordering": true,        
        "info": true,            
        "autoWidth": false,
        "pageLength": 10,        
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ appointments",
            "paginate": {
                "previous": "Prev",
                "next": "Next"
            }
        }
    });
});
</script>

@endsection
