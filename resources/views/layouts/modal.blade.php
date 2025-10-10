<!-- Modal -->
<div class="modal fade" id="ajax-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog" id="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title title fs-16px" id="modal-title">{{get_phrase('Modal title')}}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          
        </div>
      </div>
    </div>
</div>

<!-- delete modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-title" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content pt-2">
            <div class="modal-body text-center">
                <div class="icon icon-confirm">
                    <svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="24" height="24"><path d="M12,0A12,12,0,1,0,24,12,12.013,12.013,0,0,0,12,0Zm0,22A10,10,0,1,1,22,12,10.011,10.011,0,0,1,12,22Z"/><path d="M12,5a1,1,0,0,0-1,1v8a1,1,0,0,0,2,0V6A1,1,0,0,0,12,5Z"/><rect x="11" y="17" width="2" height="2" rx="1"/></svg>
                </div>
                <p class="title">{{get_phrase('Are you sure?')}}</p>
                <p class="text-muted">{{get_phrase("You can't bring it back!")}}</p>

            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn ol-btn-secondary fw-500" data-bs-dismiss="modal">{{get_phrase('Cancel')}}</button>
                <a href="" id="save-btn" class="confirm-btn btn cap-btn-primary  ol-btn-success fw-500">{{get_phrase('Confirm')}}</a>
            </div>
        </div>
    </div>
</div>
<!-- confirm modal -->
<div class="modal fade" id="confirm-modal" tabindex="-1" aria-labelledby="confirm-title" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content pt-2">
            <div class="modal-body text-center">
                <div class="icon icon-confirm">
                    <svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="24" height="24"><path d="M12,0A12,12,0,1,0,24,12,12.013,12.013,0,0,0,12,0Zm0,22A10,10,0,1,1,22,12,10.011,10.011,0,0,1,12,22Z"/><path d="M12,5a1,1,0,0,0-1,1v8a1,1,0,0,0,2,0V6A1,1,0,0,0,12,5Z"/><rect x="11" y="17" width="2" height="2" rx="1"/></svg>
                </div>
                <p class="title">{{get_phrase('Are you sure?')}}</p>
                <p class="text-muted">{{get_phrase("Once approved, this action cannot be reversed and will be finalized.")}}</p>

            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn ol-btn-secondary fw-500" data-bs-dismiss="modal">{{get_phrase('Cancel')}}</button>
                <a href="" id="confirm-btn" class="confirm-btn btn cap-btn-primary  ol-btn-success fw-500">{{get_phrase('Confirm')}}</a>
            </div>
        </div>
    </div>
</div>

<!-- edit modal -->
<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="delete-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" id="edit-modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title title fs-16px" id="delete-modal-title">{{get_phrase('Modal title')}}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          
        </div>
      </div>
    </div>
</div>
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="appointmentForm_data" action="{{ route('appointments.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Appointment Date</label>
                        <input type="date" class="form-control" id="appointmentDate" name="date" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>                    
                    <input type="hidden" name="listing_type" id="segment_type" value="{{!empty($segment_type) ? $segment_type : '' }}">
                    <input type="hidden" name="listing_id" id="segment_id"  value="{{!empty($segment_id) ? $segment_id : '' }}">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="message" id="message" cols="30" rows="3" placeholder="{{ get_phrase('Write your description') }}" class="form-control"></textarea>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save appointment</button>
                </div>
                </div>
            </form>
        </div>
    </div>
{{-- @push('js') --}}


{{-- Work Modal --}}
<div class="modal fade" id="appointmentModalWork" tabindex="-1">
  <div class="modal-dialog">
    <form id="appointmentFormWork" method="POST" action="{{ route('appointments.store') }}">
        @csrf
        <input type="hidden" name="segment_type" value="work">
        <input type="hidden" class="appointmentDate" name="date">
        {{-- form fields --}}
    </form>
  </div>
</div>

{{-- Play Modal --}}
<div class="modal fade" id="appointmentModalPlay" tabindex="-1">
  <div class="modal-dialog">
    <form id="appointmentFormPlay" method="POST" action="{{ route('appointments.store') }}">
        @csrf
        <input type="hidden" name="segment_type" value="play">
        <input type="hidden" class="appointmentDate" name="date">
        {{-- form fields --}}
    </form>
  </div>
</div>

<!-- Event Booking Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <form id="eventBookingForm" method="POST" action="{{ route('customerBookAppointment') }}">
        @csrf
        <div class="modal-header py-2">
          <h5 class="modal-title">Book Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body py-2 px-3">
          <input type="hidden" id="modal_event_id" name="event_id">
          <input type="hidden" id="modal_event_price" name="event_price">
          <input type="hidden" id="listing_type" name="listing_type" value="event">

          <!-- Event Title (Full Row) -->
          <div class="mb-2">
            <label class="form-label mb-1">Event Title</label>
            <input type="text" id="modal_event_title" name="title"  class="form-control form-control-sm" readonly>
          </div>

          <!-- Two-column Row: Date + Price -->
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label mb-1">Event Date</label>
              <input type="text" id="modal_event_date" name="date" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-6">
              <label class="form-label mb-1">Price per Person</label>
              <input type="text" id="modal_event_base_price" class="form-control form-control-sm" readonly>
            </div>
          </div>

          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label mb-1">Person Count</label>
              <input type="number" id="modal_event_persons" name="adults" class="form-control form-control-sm" min="1" value="1">
            </div>
            <div class="col-6">
              <label class="form-label mb-1 fw-bold">Total Price</label>
              <input type="text" id="modal_event_total" class="form-control form-control-sm fw-bold" readonly>
            </div>
          </div>
        </div>

        <div class="modal-footer py-2">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Submit Booking</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    "use script"
    function modal(size, url, title){
        $('#modal-dialog').addClass(size);
        if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $('#modal-dialog .modal-body').html(data);
                },
                error: function() {
                    $('#modal-dialog .modal-body').html('<p>Error loading content.</p>');
                }
            });
        }
        $('#modal-title').html(title);
        $('#ajax-modal').modal('show');
    
    }
    function edit_modal(size, url, title){
        $('#edit-modal-dialog').addClass(size);
        if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $('#edit-modal-dialog .modal-body').html(data);
                },
                error: function() {
                    $('#edit-modal-dialog .modal-body').html('<p>Error loading content.</p>');
                }
            });
        }
        $('#delete-modal-title').html(title);
        $('#edit-modal').modal('show');
    }

    function delete_modal(url){
        $('#delete-modal').modal('show');
        $("#save-btn").attr("href",url);
    }


    function confirm_modal(url){
        $('#confirm-modal').modal('show');
        $("#confirm-btn").attr("href",url);
    }
</script>    
<script>    

let userLoggedIn = false; 

// Event click handler
function selectEvent(key, id, title, price, date) {
    @if(auth()->check())

    // Fill modal fields
    document.getElementById('modal_event_id').value = id;
    document.getElementById('modal_event_title').value = title;
    document.getElementById('modal_event_date').value = date;
    document.getElementById('modal_event_base_price').value = "₹" + parseFloat(price).toLocaleString();
    document.getElementById('modal_event_price').value = price;
    document.getElementById('modal_event_persons').value = 1;
    document.getElementById('modal_event_total').value = "₹" + parseFloat(price).toLocaleString();

    // Show modal
    let modal = new bootstrap.Modal(document.getElementById('eventModal'));
    modal.show();
    @else
        window.location.href = "{{ route('login') }}?redirect=" + encodeURIComponent(window.location.href);
    @endif
}

// Auto-calculate total when person count changes
document.getElementById('modal_event_persons').addEventListener('input', function () {
    let basePrice = parseFloat(document.getElementById('modal_event_price').value) || 0;
    let persons = parseInt(this.value) || 1;
    let total = basePrice * persons;

    document.getElementById('modal_event_total').value = "₹" + total.toLocaleString();
    document.getElementById('modal_event_price').value = total; // update hidden total for submission
});

// Handle form submission (optional AJAX)
document.getElementById('eventBookingForm').addEventListener('submit', function (e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Event booked successfully!');
            let modalEl = document.getElementById('eventModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        } else {
            alert(data.message || 'Something went wrong.');
        }
    })
    .catch(() => alert('Server error, please try again.'));
});


</script>    
{{-- @endpush --}}