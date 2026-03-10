// js/booking_handler.js
$(document).ready(function() {
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