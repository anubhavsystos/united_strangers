@extends('layouts.frontend')
@push('title', get_phrase('Customer wallet'))
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
                        <h1 class="in-title-16px">{{ get_phrase('wallet') }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb cap-breadcrumb">
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="">{{ get_phrase('Home') }}</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item active" aria-current="page">{{ get_phrase('wallet') }}</li>
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

    <!-- Wallet, Coins & Rewards Table -->
    <table id="walletTable" class="table align-middle mb-3">
        <thead class="table-light">
            <tr>
                <th>{{ get_phrase('Type') }}</th>
                <th>{{ get_phrase('Description') }}</th>
                <th>{{ get_phrase('Amount') }}</th>
                <th>{{ get_phrase('Date') }}</th>
                <th>{{ get_phrase('Expiry') }}</th>
            </tr>
        </thead>
        <tbody class="ca-tbody">
            @php
                // Example dummy data (replace with DB transactions)
                $transactions = [
                    ['type' => 'Wallet', 'desc' => 'Added via Razorpay', 'amount' => '+ ₹500', 'date' => '2025-10-08', 'expiry' => null],
                    ['type' => 'Coins', 'desc' => 'Earned from booking', 'amount' => '+ 200', 'date' => '2025-10-10', 'expiry' => \Carbon\Carbon::now()->addDays(7)->format('Y-m-d')],
                    ['type' => 'Reward', 'desc' => 'Referral bonus', 'amount' => '+ ₹50', 'date' => '2025-10-09', 'expiry' => null],
                ];
            @endphp

            @foreach ($transactions as $tx)
                <tr class="ca-tr">
                    <td><span class="in-subtitle-14px">{{ $tx['type'] }}</span></td>
                    <td>{{ $tx['desc'] }}</td>
                    <td><strong>{{ $tx['amount'] }}</strong></td>
                    <td>{{ date('d M Y', strtotime($tx['date'])) }}</td>
                    <td>
                        @if($tx['expiry'])
                            @php 
                                $expiryDate = \Carbon\Carbon::parse($tx['expiry']);
                                $now = \Carbon\Carbon::now();
                                $isExpiringSoon = $expiryDate->diffInDays($now) <= 7;
                            @endphp
                            <span class="{{ $isExpiringSoon ? 'text-danger fw-bold' : '' }}">
                                {{ $expiryDate->format('d M Y') }}
                            </span>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Convert Coins to Wallet Button -->
    <div class="d-flex justify-content-end mt-2">
        <button id="convertCoinsBtn" class="btn btn-sm btn-primary">
            <i class="fas fa-exchange-alt"></i> {{ get_phrase('Convert Coins to Wallet') }}
        </button>
    </div>

    <!-- Terms and Conditions -->
    <div class="mt-4 p-3 rounded" style="background-color:#f9f9f9;">
        <h6 class="fw-bold mb-2">{{ get_phrase('Wallet, Coins & Rewards - Terms & Conditions') }}</h6>
        <ul class="small mb-0">
            <li>Customers can <strong>add money</strong> to their wallet via the platform’s payment gateway.</li>
            <li>Wallet balance can be used to <strong>pay in full</strong> or <strong>split payments</strong> during checkout.</li>
            <li>Coins are <strong>earned for actions</strong> like bookings, reviews, referrals, and event check-ins.</li>
            <li>The conversion rate between coins and INR is <strong>defined by the platform admin</strong>.</li>
            <li>Coins <strong>expire in 7 days</strong> from the date they are earned unless used or converted.</li>
            <li>On cancellations, <strong>coins or wallet credits may be reversed</strong> as per policy.</li>
            <li>Abuse detection systems monitor multiple accounts, device usage, and suspicious conversions.</li>
            <li>Manual review may apply for <strong>large conversions or irregular activity</strong>.</li>
        </ul>
    </div>

</div>

<script>
// $(document).ready(function() {
//     $('#walletTable').DataTable({
//         "pageLength": 5,
//         "language": {
//             "search": "{{ get_phrase('Search:') }}",
//             "lengthMenu": "{{ get_phrase('Show _MENU_ entries') }}",
//             "info": "{{ get_phrase('Showing _START_ to _END_ of _TOTAL_ entries') }}"
//         }
//     });

//     // Convert Coins to Wallet - Example Action
//     $('#convertCoinsBtn').on('click', function() {
//         if (confirm("{{ get_phrase('Are you sure you want to convert your coins to wallet balance?') }}")) {
//             $.ajax({
//                 url: "{{ route('user.convert_coins_to_wallet') }}", // Define this route in your web.php
//                 type: "POST",
//                 data: {
//                     _token: "{{ csrf_token() }}"
//                 },
//                 success: function(response) {
//                     alert(response.message || "{{ get_phrase('Conversion successful!') }}");
//                     location.reload();
//                 },
//                 error: function() {
//                     alert("{{ get_phrase('Something went wrong, please try again later.') }}");
//                 }
//             });
//         }
//     });
// });
</script>

                   
                </div>
            </div>
        </div>
    </section>



@endsection
