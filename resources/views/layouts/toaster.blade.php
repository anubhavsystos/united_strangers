<div class="toast-container position-fixed top-0 end-0 p-3"></div>
<script>
    "use strict";

    function toaster_message(type, icon, header, message) {
        var toasterMessage = '<div class="toast ' + type +
            ' fade text-12" role="alert" aria-live="assertive" aria-atomic="true" class="rounded-3"><div class="toast-header"> <i class="' +
            icon + ' me-2 mt-2px text-14 d-flex"></i> <strong class="me-auto"> ' + header +
            ' </strong><small>{{ get_phrase('Just Now') }}</small><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div><div class="toast-body">' +
            message + '</div></div>';
        $('.toast-container').prepend(toasterMessage);
        const toast = new bootstrap.Toast('.toast')
        toast.show()
    }

    function success(message) {
        toaster_message('success', 'far fa-check-circle', '{{ get_phrase('Success !') }}', message);
    }
    function warning(message) {
        toaster_message('warning', 'far fa-check-circle', '{{ get_phrase('Attention !') }}', message);
    }

    function error(message) {
        toaster_message('error', 'fi-sr-triangle-warning', '{{ get_phrase('An Error Occurred !') }}', message);
    }
</script>

@if ($message = Session::get('success'))
    <script>
        "use strict";
        success("{{ $message }}");
    </script>
    @php Session()->forget('success'); @endphp
@elseif($message = Session::get('warning'))
    <script>
        "use strict";
        warning("{{ $message }}");
    </script>
    @php Session()->forget('warning'); @endphp
@elseif($message = Session::get('error'))
    <script>
        "use strict";
        error("{{ $message }}");
    </script>
    @php Session()->forget('error'); @endphp
@elseif(isset($errors) && $errors->any())
    @php
        $message = '<ul>';
        foreach ($errors->all() as $error):
            $message .= '<li>' . $error . '</li>';
        endforeach;
        $message .= '</ul>';
    @endphp
    <script>
        "use strict";
        error("{!! $message !!}");
    </script>
@endif
<script>
      document.addEventListener('DOMContentLoaded', function () {
    let calendar;                          
    const calTabBtn   = document.getElementById('calander-tab');   
    const calTabPane  = document.getElementById('calander');   
    const calendarEl  = document.getElementById('calendar');
    const modalEl     = document.getElementById('appointmentModal');

    function initCalendarOnce() {
        if (calendar) return; 

        const segment_type = document.getElementById('segment_type').value;
        const segment_id   = document.getElementById('segment_id').value;

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            events: "{{ route('appointments.fetch') }}" 
                     + "?segment_id=" + segment_id 
                     + "&segment_type=" + segment_type,
            dateClick: function(info) {
                document.getElementById('appointmentDate').value = info.dateStr;
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });

        calendar.render();
        window.calendar = calendar;
    }

    if (calTabBtn) {
        calTabBtn.addEventListener('shown.bs.tab', function () {
            initCalendarOnce();
            setTimeout(() => calendar.updateSize(), 50);
        });
    }

    if (calTabPane && calTabPane.classList.contains('active')) {
        initCalendarOnce();
    }
});
</script>
<script>
        document.addEventListener('DOMContentLoaded', function () {
        let calendar;                          
        const calTabBtn = document.getElementById('calander-tab');   
        const calTabPane = document.getElementById('calander');   
        const calendarEl = document.getElementById('calendar');
        const modalEl = document.getElementById('appointmentModal');

        function initCalendarOnce() {
            if (calendar) return; 

            calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            dateClick: function(info) {
                document.getElementById('appointmentDate').value = info.dateStr;
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
            });

            calendar.render();
        }
        if (calTabBtn) {
            calTabBtn.addEventListener('shown.bs.tab', function () {
            initCalendarOnce();
            setTimeout(() => calendar.updateSize(), 50);
            });
        }

        if (calTabPane && calTabPane.classList.contains('active')) {
            initCalendarOnce();
        }
        });
</script>
<script>
function appointmentForm_data(e) {
    e.preventDefault();
    const type = document.getElementById("type");
    const listing_id = document.getElementById("listing_id");
    const form = document.getElementById("appointmentForm_data");
    const formData = new FormData(form);
    fetch(form.action, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log("appointment Saved:", data);
        form.reset();
        bootstrap.Modal.getInstance(document.getElementById("appointmentModal")).hide();
        if (window.calendar) {
            window.calendar.refetchEvents();
        }
    })
    .catch(err => console.error(err));
}
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("appointmentForm_data").addEventListener("submit", appointmentForm_data);
});
</script>