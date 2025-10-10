<div class="container text-center">
    <h4>{{ get_phrase('Your Booking QR Code') }}</h4>
    <p>Scan this at the gate to confirm your arrival.</p>
    <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code" width="200">
    <p><strong>Token:</strong> {{ $appointment->qr_code }}</p>
</div>