// js/booking_handler.js
$(document).ready(function() {
    // Dynamic Slot Disable Logic
    $('input[name="date"]').on('change', function() {
        const date = $(this).val();
        const service_id = $('input[name="service_id"]').val();
        const timeSelect = $('select[name="time"]');
        
        if (!date) return;
        
        // Reset previously disabled options
        timeSelect.find('option').prop('disabled', false).text(function() {
            return $(this).text().replace(' (Booked)', '');
        });
        
        $.get('../actions/fetch_booked_slots.php', { service_id: service_id, date: date }, function(bookedSlots) {
            if (bookedSlots && bookedSlots.length > 0) {
                timeSelect.find('option').each(function() {
                    const val = $(this).val();
                    if (val && bookedSlots.includes(val)) {
                        $(this).prop('disabled', true).text($(this).text() + ' (Booked)');
                    }
                });
            }
        }, 'json');
    });

    // Form Submission Logic
    $('#booking-form').on('submit', function(e) {
        e.preventDefault();
        
        const btn = $(this).find('button[type="submit"]');
        const originalText = btn.text();
        
        // Visual feedback
        btn.html('<span class="spinner-border spinner-border-sm"></span> Securing Slot...')
           .prop('disabled', true);

        $.ajax({
            url: '../actions/book_appointment_action.php', 
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Appointment Scheduled',
                        text: res.message,
                        background: '#1a1033',
                        color: '#fff',
                        confirmButtonColor: '#9d4edd'
                    }).then(() => {
                        window.location.href = '../user/my_appointments.php'; 
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Booking Failed',
                        text: res.message,
                        background: '#1a1033',
                        color: '#fff'
                    });
                }
            },
            error: function(xhr) {
                console.error("Server Error:", xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Unable to connect to the booking server. Please try again later.',
                    background: '#1a1033',
                    color: '#fff'
                });
            },
            complete: function() {
                btn.html(originalText).prop('disabled', false);
            }
        });
    });
});