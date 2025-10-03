@extends('layouts.frontend')
@push('title', get_phrase('Customer Appointment'))
@push('meta')@endpush
@section('frontend_layout')
<div class="container py-5 text-center">

    <div class="card shadow-lg p-5 border-0 rounded-3 mx-auto" style="max-width: 600px;">
        <div class="mb-4">
            <img src="{{ asset('/uploads/logo/1755760934_logo.png') }}" 
                 alt="Success" 
                 width="120">
        </div>

        <h1 class="mb-3 text-success fw-bold">🎉 Thank You!</h1>
        <p class="lead text-muted mb-4">
            Your payment was successful and your appointment is now confirmed.
        </p>

        <div class="alert alert-success">
            <strong>✔ Payment Confirmed:</strong> We’ve sent you a confirmation email.
        </div>

        <a href="{{ route('customer.appointment') }}" class="btn btn-primary mt-3">
            Back to My Appointments
        </a>
    </div>

</div>
@endsection
