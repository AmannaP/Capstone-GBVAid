$(document).ready(function() {
    // Cancel Appointment Logic
    $('.cancel-btn').click(function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Cancel Session?',
            text: "This will remove this session from your upcoming appointment schedule. This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            background: '#1a1033', // Dark background
            color: '#ffffff',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#3c2a61',
            confirmButtonText: 'Yes, Cancel it',
            BarProp: 'rgba(15, 10, 30, 0.8)'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...', 
                    background: '#1a1033',
                    color: '#ffffff',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                    
                });
                
                $.post('../actions/cancel_appointment_action.php', { appointment_id: id }, function(res) {
                    if (res.trim() === 'success') {
                        Swal.fire({
                            title: 'Cancelled',
                            text: 'Your appointment has been cancelled.',
                            icon: 'success',
                            background: '#1a1033',
                            color: '#ffffff',
                            confirmButtonColor: '#9d4edd'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Could not cancel appointment. Server returned: ' + res,
                            icon: 'error',
                            background: '#1a1033',
                            color: '#ffffff'
                        });
                    }
                }).fail(function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Network error.',
                        icon: 'error',
                        background: '#1a1033',
                        color: '#ffffff'
                    });
                });
            }
        });
    });
});